FROM composer
LABEL maintainer='artoo-dev@facile.it'

RUN apk add --update linux-headers
RUN pecl channel-update pecl.php.net
RUN pecl bundle -d /usr/src/php/ext/ xdebug
RUN docker-php-ext-install xdebug
RUN touch /usr/local/etc/php/conf.d/date.ini
RUN chmod o+w /usr/local/etc/php/conf.d/date.ini
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
