#!/bin/bash

# Pest PHP Test Runner Helper

if [ "$1" == "all" ]; then
    php artisan test
elif [ "$1" == "unit" ]; then
    php artisan test tests/Unit
elif [ "$1" == "feature" ]; then
    php artisan test tests/Feature
elif [ "$1" == "coverage" ]; then
    php artisan test --coverage
elif [ "$1" == "parallel" ]; then
    php artisan test --parallel
elif [ "$1" == "watch" ]; then
    php artisan test --watch
elif [ "$1" == "seed" ]; then
    php artisan seed:test-data --count=${2:-5}
elif [ "$1" == "help" ] || [ -z "$1" ]; then
    echo "Pest PHP Test Runner"
    echo ""
    echo "Usage: ./test.sh [command] [options]"
    echo ""
    echo "Commands:"
    echo "  all         Run all tests"
    echo "  unit        Run unit tests only"
    echo "  feature     Run feature tests only"
    echo "  coverage    Run tests with coverage report"
    echo "  parallel    Run tests in parallel"
    echo "  watch       Watch and re-run tests on file changes"
    echo "  seed [n]    Seed test data (n records, default 5)"
    echo "  help        Show this help message"
    echo ""
    echo "Examples:"
    echo "  ./test.sh all"
    echo "  ./test.sh feature"
    echo "  ./test.sh coverage"
    echo "  ./test.sh seed 10"
else
    echo "Unknown command: $1"
    echo "Run './test.sh help' for usage information"
    exit 1
fi
