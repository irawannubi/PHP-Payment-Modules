Installation Instructions
================================================================================

## Introduction

This is not recommend to serve as a library for beginners. Instead, it's a fully implemented framework for providing interfaces to specific payment gateways. See README for a full list of supported gateways.

This framework can be used in piecemeal, and easily implemented into app. Below is the key components of the framework.

## Database

The database is utilizing MySQL, and is used to store the required setting names/values for each gateway. It can easily be SQLite, MSSQL, MongoDb, or really anything at all.

All unit tests are using a Mocked Db, with the settings stored in an array.

The database dump is located at `/mysqlDatabase.sql`.

The connection settings are stored in `/config/Application.php`. Please adjust it to your settings, along with using a nice encryption key. Encryption is used solely for storing sensitive usernames/passwords.

## Encryption

A sample encryption implementation can be found in `/library/General.php`, and the static methods are `encrypt()` and `decrypt()`.

## index.php

`/index.php` serves as a sample implementation for Authorize.net, and results in either "Denied" or "Approved", with the raw response outputted. This file serves no need other than for demonstration purposes, and should be removed in your application.

## tests/

All gateways and helper classes are Unit Tested through PHPUnit. Please ensure all tests are passing at all times in your application to ensure the Gateway APIs have not become outdated.

## library/payments/modules

This is where the meat and potatoes are for this framework. Each gateway has its own class, and sample implementation at the top of the class before definition. See the top of each gateway for more information, and the top of `/library/payments/Modules.php`, which serves at he entry-point into the framework.