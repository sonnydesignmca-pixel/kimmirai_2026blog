# ==========================================
# 1. フロントエンド（Vite）のビルドステージ
# ==========================================
FROM node:24-slim AS frontend-builder
WORKDIR /build
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ==========================================
# 2. 本番環境（PHP / Nginx）のステージ
# ==========================================
FROM webdevops/php-nginx:8.4

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 所有者を application ユーザーにしてコピーする
COPY --chown=application:application . .

# 💡 修正ポイント: ステージ1で作成したビルド成果物（public/build）だけをコピーする
COPY --from=frontend-builder --chown=application:application /build/public/build ./public/build

# Laravelの実行に必要な空フォルダを生成し、権限を付与する
RUN mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/logs \
    && chown -R application:application storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

# npmのパッケージをインストールし、本番用にビルドする
RUN npm ci && npm run build

# webdevops用のドキュメントルート設定
ENV WEB_DOCUMENT_ROOT=/app/public

# Laravel config
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1
