# Development

If you need to create a new Client library, the most simple way is to follow the patterns from the existing classes.
The `AbstractClient` provides a handful of helping methods.

## Tests

The client responsability is basically mapping class methods to API endpoints. To easily test these mappings, follow
the existing patterns from `tests/Clients/`
