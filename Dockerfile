# ==========================================
# 1. フロントエンド（Vite）のビルドステージ
# ==========================================
FROM node:24-slim AS frontend-builder
WORKDIR /build
# 💡 修正ポイント1: Composerを一時的にインストール（vendorフォルダ生成のため）
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# PHPの依存関係を解決するために必要な最小限のツールをインストール
RUN apt-get update && apt-get install -y git unzip libpq-dev php-cli php-mbstring || true
COPY package*.json ./
RUN npm ci
COPY . .
RUN composer install --no-dev --optimize-autoloader
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


WORKDIR /app

# 所有者を application ユーザーにしてコピーする
COPY --chown=application:application . .

COPY --from=frontend-builder --chown=application:application /build/public/build ./public/build
COPY --from=frontend-builder --chown=application:application /build/vendor ./vendor

# Laravelの実行に必要な空フォルダを生成し、権限を付与する
RUN mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/logs \
    && chown -R application:application storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache


# webdevops用のドキュメントルート設定
ENV WEB_DOCUMENT_ROOT=/app/public

# Laravel config
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1
