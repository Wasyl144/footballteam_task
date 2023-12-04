FROM node:18.19.0-bookworm

WORKDIR /app

COPY docker/frontend/conf/fe_startup.sh /tmp/fe_startup.sh

RUN ["chmod", "+x", "/tmp/fe_startup.sh"]

EXPOSE 5173

ENTRYPOINT ["/tmp/fe_startup.sh"]