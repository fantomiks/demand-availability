FROM node:16.10-alpine AS build
WORKDIR /usr/src/app
COPY ./ui/package* ./
RUN npm install
COPY ./ui .
RUN npm run build

FROM nginx:1.19.4-alpine
COPY ./deployment/local/angular/nginx.conf /etc/nginx/nginx.conf
COPY --from=build /usr/src/app/dist/app /usr/share/nginx/html
