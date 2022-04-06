# Application image
#
# This image mainly adds the latest application source to the base image
#
FROM fdc101082/dev.api.karkee.biz:app-api-1

ARG NODE_ENV=staging
ARG API_URL=https://api.staging.karkee.biz
ARG PLATFORM_API=api
ARG SERVER_PORT=4000

ENV NODE_ENV=${NODE_ENV}
ENV API_URL=${API_URL}
ENV PLATFORM_API=${PLATFORM_API}
ENV SERVER_PORT=${SERVER_PORT}

# Copy PHP configuration into the image
COPY ./config/php/productive.ini /etc/php7/conf.d/90-productive.ini

# Copy the app code into the image
COPY . /var/www/html

# Create required directories listed in .dockerignore
RUN mkdir -p runtime web/assets var/sessions \
    && chown www-data:www-data runtime web/assets var/sessions

# Let docker create a volume for the session dir.
# This keeps the session files even if the container is rebuilt.
VOLUME /var/www/html/var/

WORKDIR /var/www/html

EXPOSE ${SERVER_PORT}