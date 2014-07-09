#### PHP livelink Extractor and Parser 
---

This script allow to extract all content in a livelink platform, for auditing and clone a livelink platform. The way to do this is using the provided xml API from livelink, unfortunately, theres no public documentation of this API, but this script can perform an effective work and way to clone a complete livelink platform.

####In this repo, has 2 php script:

> - livelink.php
> - parser.php

**livelink.php** contains all code to perform a cURL request for extract a xml file with all directory structure and base64 data files.

**parser.php** contains all code to re create a clone version on the livelink platform in your hard drive.

***I hope this code will be useful to perform a livelink extraction.***


