# Curl Wrapper Class

It isn't necessary to code longer anymore!

```php
require_once("curl.class.php");

$curl = new Curl();
$curl->URL("https://www.example.com")->exec();
```

---

## Installation
It is pretty simple to utilize this class, you just need to require it.

```php
require_once("curl.class.php");
```

## Initialization
You can custom Initialize Default options when initializing.

When $this->reset() function have been called, all options will rollback to Initialize Default.

```php
// Initialize without Initialize Default.
$curl = new Curl();

// Initialize with CURLOPT_URL option as Initialize Default.
$curl = new Curl("https://www.example.com");

// Initialize with multiple options as Initialize Default.
$curl = new Curl(array(
	"URL" => "https://www.example.com",
	"CURLOPT_CONNECTTIMEOUT" => 30,
	CURLOPT_TIMEOUT => 60
));

// Initialize using option from another Curl object as Initialize Default.
$new_curl = new Curl($curl);
```

You can reinitialize with new Initialize Default options by $this->init() function.

```php
// Reinitialize without Initialize Default.
$curl->init();

// Reinitialize with CURLOPT_URL option as Initialize Default.
$curl->init("https://www.example.com");

// Reinitialize with multiple options as Initialize Default.
$curl->init(array(
	"URL" => "https://www.example.com",
	"CURLOPT_CONNECTTIMEOUT" => 30,
	CURLOPT_TIMEOUT => 60
));

// Reinitialize using option from another Curl object as Initialize Default.
$new_curl->init($curl);

// Reinitialize using current option from itself as Initialize Default.
$curl->init(true);
```

## Set an option to Curl Handle
There are several ways to set an option, but they will work in the same way.

```php
// Example for set "CURLOPT_URL" option.

$curl->URL = "https://www.example.com";
$curl->CURLOPT_URL = "https://www.example.com";

$curl->setopt("URL", "https://www.example.com/");
$curl->setopt("CURLOPT_URL", "https://www.example.com");
$curl->setopt(CURLOPT_URL, "https://www.example.com");

$curl->URL("https://www.example.com");
$curl->CURLOPT_URL("https://www.example.com");
```

## Set multiple options to Curl Handle
You can set multiple options on Curl Handle with $this->setopt_array() function.

```php
// Example for set "CURLOPT_CONNECTTIMEOUT" and "CURLOPT_TIMEOUT" options.

$curl->setopt_array(array(
	"CONNECTTIMEOUT" => 30,
	"TIMEOUT" => 60
));

$curl->setopt_array(array(
	"CURLOPT_CONNECTTIMEOUT" => 30,
	"CURLOPT_TIMEOUT" => 60
));

$curl->setopt_array(array(
	CURLOPT_CONNECTTIMEOUT => 30,
	CURLOPT_TIMEOUT => 60
));
```

## Execute Curl Session
You can execute by $this->exec() function.

This function will return result from execution.

```php
$result = $curl->exec();
```

After execute, you can use these variables.

```php
$curl->errno; // Error Number (Int)
$curl->error; // Error Text (String)
$curl->result; // Result of Execution (String/Bool)
$curl->info; // Information (Array)
```

## Get Information
You can get information from handle by $this->getinfo() function.

This function will return information as Array.

```php
// Get all information from handle.
$info = $curl->getinfo();

// Get information with CURLINFO_EFFECTIVE_URL option.
$info = $curl->getinfo("EFFECTIVE_URL");
$info = $curl->getinfo("CURLINFO_EFFECTIVE_URL");
$info = $curl->getinfo(CURLINFO_EFFECTIVE_URL);
```

You can use $this->info variable to get all information after execute Curl Session.

## Reset all options to Initialize Default
You can reset all options to Initialize Default by $this->reset() function.

```php
// Initialize with Initialize Default options.
$curl = new Curl(array(
	"URL" => "https://www.example.com",
	"TIMEOUT" => 30
));

$curl->TIMEOUT(10); // Override CURLOPT_TIMEOUT to 10
$curl->exec(); // Execute with CURLOPT_TIMEOUT = 10

$curl->reset(); // Reset all options to Initialize Default.
$curl->exec(); // Execute with CURLOPT_TIMEOUT = 30

```

## Other Curl functions
You can use other Curl functions by calling function on wrapper without "curl_" prefix.

If that function require Curl Handle, this wrapper will fill it for you.

```php
// Example for curl_version()
$curl->version();

// Example for curl_escape($ch, $str)
$curl->escape($str);

```

*However, this wrapper doesn't support all "curl_multi" and "curl_share" functions yet.*

---

### Note
- This wrapper doesn't support all "curl_multi" and "curl_share" functions *yet*.
- CURLOPT_RETURNTRANSFER will set to TRUE by default.
- If cacert.pem file exists, CURLOPT_SSL_VERIFYPEER will set to TRUE by default.
- If cacert.pem file exists, CURLOPT_CAINFO will set to cacert.pem's realpath by default.
