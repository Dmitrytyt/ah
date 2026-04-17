FROM nginx:alpine

# Удаляем дефолтный конфиг
RUN rm /etc/nginx/conf.d/default.conf

# Копируем свой конфиг
COPY /conf.d/nginx.conf /etc/nginx/nginx.conf
