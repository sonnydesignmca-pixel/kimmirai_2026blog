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

# Laravelの実行に必要な空フォルダを生成し、権限を付与する
RUN mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/logs \
    && chown -R application:application storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Node.jsとnpmをインストール
    RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs

RUN composer install --no-dev --optimize-autoloader

#依存性をダウンロードしてnpmビルドを実行
RUN npm install && npm run build

# webdevops用のドキュメントルート設定
ENV WEB_DOCUMENT_ROOT=/app/public

# Laravel config
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]
