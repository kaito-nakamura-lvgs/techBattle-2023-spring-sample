FROM python:3.9.6

RUN mkdir /app
WORKDIR /app

RUN apt-get update
RUN apt-get -y install locales && \
    localedef -f UTF-8 -i ja_JP ja_JP.UTF-8
ENV LANG ja_JP.UTF-8
ENV LANGUAGE ja_JP:ja
ENV LC_ALL ja_JP.UTF-8
ENV TZ JST-9
ENV TERM xterm

RUN apt-get install -y vim less
RUN pip install --upgrade pip
RUN pip install --upgrade setuptools

COPY ./opt/convert_bug.pyc ./convert_bug.pyc

EXPOSE 8000
CMD ["python3", "/app/convert_bug.pyc"]