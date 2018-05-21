# Slim 3 Very simple REST Skeleton

This is a simple skeleton project for Slim 3 that implements a simple REST API.
Based on [https://github.com/pabloroca/slim3-simple-rest-skeleton] who is based on [https://github.com/moritz-h/slim3-rest-skeleton] who is based on [akrabat's slim3-skeleton](https://github.com/akrabat/slim3-skeleton)

## Purpose

My own fork of Pablo Roca skeleton does not change a lot. Check his reamde.md for using it.
Differences:
- on OAuth verification: I prefered using middleware instead of an oauth base controller.
- removed APIRateLimiter
- renamed some class names
- added response serializer

## Information

https://github.com/pabloroca/slim3-simple-rest-skeleton

## Personal notes

Steps to have a running server

• git clone...
• composer install...
• create oauth db
• create project db (with a user table)
• change `OAuth2_CustomStorage` to corresponds to user table
• try routes:
  • ...
  • ...