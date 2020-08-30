PHP Static Analyzer
===================

TBD

Installation
------------

TBD

Usage
-----

**Analyzing class methods and properties**


For analyzing class methods and properties call `class-info-by-name` command with two required arguments

```bash
$ php bin/console class-info-by-name [project_path] [class_name]
```

Where `project_path` argument is path to directory when need find class (root path of project for example) 
and `class_name` argument -  name of class for analyzing.



Code style fixer
----------------

To check the code style just run the following command


```bash
$ composer cs-check
```


to fix the code style run next command

```bash
$ composer cs-fix
```

Demo
-----

**Analyzing class methods and properties**

To test this functionality, you can call the command with the specified parameters

```bash
$ php bin/console class-info-by-name tests AnalyzerTester
```

or just call composer script `tester`

```
$ composer tester
```


License
-------

[![license](https://img.shields.io/github/license/greeflas/default-project.svg)](LICENSE)

This project is released under the terms of the BSD-3-Clause [license](LICENSE).

Copyright (c) 2020, Volodymyr Kupriienko
