SilverStripe Simple Library Module
==================================

Still in early alpha phase. Leans heavily on modeladmin but there's Issue/Return/Renew forms on the front end.

A lot to still do before it's usable - anyone who is keen to help develop contact me.

- Integrates with Google Books API for easy addition of books.
- Supports multiple holding of each resource (Book/CD/DVD etc...)

## Installation
As per usual - composer name is howardgrigg/simple-library
To use the Google Books API get an API key from https://code.google.com/apis/console/ then set ```Resource::$googlebooksapi_key = "";``` in your config.