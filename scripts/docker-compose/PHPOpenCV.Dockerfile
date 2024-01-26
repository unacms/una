FROM ubuntu:22.04

RUN apt update && apt install -y wget pkg-config cmake git checkinstall

RUN apt update && apt install -y gcc-arm-linux-gnueabi python3 libjpeg-dev libpng-dev build-essential 
RUN apt update && apt install -y wget pkg-config cmake git checkinstall
RUN cd /opt && git clone https://github.com/opencv/opencv_contrib.git && git clone https://github.com/opencv/opencv.git
RUN cd /opt/opencv_contrib && git checkout tags/4.7.0 && cd ../opencv && git checkout tags/4.7.0
RUN cd /opt && mkdir build && cd build && cmake -D OPENCV_GENERATE_PKGCONFIG=YES -D CMAKE_BUILD_TYPE=RELEASE -D CMAKE_INSTALL_PREFIX=/usr/local -D OPENCV_EXTRA_MODULES_PATH=../opencv_contrib/modules ../opencv
RUN cd /opt/build && make -j4 && ldconfig && make install
### RUN wget https://raw.githubusercontent.com/php-opencv/php-opencv-packages/master/opencv_4.7.0_amd64.deb && dpkg -i opencv_4.7.0_amd64.deb && rm opencv_4.7.0_amd64.deb

RUN apt update && export DEBIAN_FRONTEND=noninteractive && apt install -y software-properties-common && add-apt-repository ppa:ondrej/php && apt update && apt install -y php8.2 php8.2-dev

RUN export DEBIAN_FRONTEND=noninteractive && apt install -y php8.2-cli php8.2-fpm php8.2-gd php8.2-curl php8.2-zip php8.2-xml php8.2-mbstring php8.2-mysql php8.2-imagick php8.2-intl

RUN cd /opt && git clone https://github.com/php-opencv/php-opencv.git

RUN cd /opt/php-opencv && phpize && ./configure --with-php-config=/usr/bin/php-config && make

# build deb package:

RUN cd /opt/php-opencv && checkinstall --default --type debian --install=no --pkgname php-opencv --pkgversion "8.2-4.7.0" --pkglicense "Apache 2.0" --pakdir ~ --maintainer "php-opencv" --addso --autodoinst make install

RUN echo "extension=opencv.so" > /etc/php/8.2/cli/conf.d/opencv.ini && echo "extension=opencv.so" > /etc/php/8.2/fpm/conf.d/opencv.ini

RUN sed -r -i 's/listen = .*/listen = 9000/g' /etc/php/8.2/fpm/pool.d/www.conf 

RUN mkdir /var/run/php

EXPOSE 9000

CMD ["/usr/sbin/php-fpm8.2", "-F"]
# CMD ["tail", "-f", "/dev/null"]
