FROM nginx:latest

RUN usermod -u 1000 -o www-data
WORKDIR /home/www-data/workspace/laravel
RUN chown -R www-data:www-data /home/www-data/workspace/laravel