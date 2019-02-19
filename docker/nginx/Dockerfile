FROM nginx:latest

LABEL maintainer="Ismael Piñeiro <ismael@adoramedia.com>"

ENV NGINX_DIR /etc/nginx
ENV NGINX_CONF_DIR $NGINX_DIR/conf.d

# Config nginx
COPY default.conf $NGINX_CONF_DIR
COPY nginx.conf $NGINX_DIR 

# run
CMD ["nginx", "-g", "daemon off;"]
