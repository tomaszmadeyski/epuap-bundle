Epuap Bundle
============
[![Build Status](https://travis-ci.org/tomaszmadeyski/epuap-bundle.svg?branch=master)](https://travis-ci.org/tomaszmadeyski/epuap-bundle)

Configuration:
--------------
```
madeyski_epuap:
    settings:
        app_id: ~ #required: application id from puap
        pub_key_path: ~ #required: path to public key
        private_key_path: ~ #required: path to private key
        url:
            post_login_redirect: ~ #required: url to redirect after sucessfull login. Can be absolute url (/homepage) or route name (@home)
```
