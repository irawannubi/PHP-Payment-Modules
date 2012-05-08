PHP File Formatting
================================================================================

## PHP Tags

The full PHP tag (&lt;?php) must always be used. Short-tags are forbidden.

For files that contain only PHP code (class files for example), the PHP closing tag (?&gt;) is forbidden.

## Indentation

Indentations are to be made with 1 tab character. Spaces for tabs are forbidden.

## Line Length

There is no maximum line length. However, the preferred maximum is 80 characters.

## Line Endings

All lines must be terminated with the Unix text file convention. That is, the \n (LF) linefeed character. Windows (CRLF) or old-style Mac OS (CR) are forbidden.


Naming Conventions
================================================================================

## Classes

All classes must belong to a namespace. Each namespace/sub-namespace is separated by an underscore. Words are CamelCase, with the first letter always being uppercase.

Acronyms should only have their first letter uppercase.

## Abstract Classes

All abstract classes follow the same naming convention as classes outlined above, except that they also must end in the word "`Abstract`". The word "`Abstract`" must not be preceded by an underscore.

## Interfaces

All interfaces follow the same naming convention as classes outlined above, except that they must end in the word "`Interface`". The word "`Interface`" must not be preceded by an underscore.

### Filenames

There is to be one class per file. Using the above naming convention, class source files are directly mapped to the filesystem by replacing each underscore with a directory separator and then adding the "`.php`" file extension. This simple convention makes it very easy to create efficient autoloaders.

Files and directories are case-sensitive.

## Functions and Methods

There are to be no functions defined. All functions must be methods of some class to act as a namespace. Classes containing only static methods should be used.

Method names must use the camelCase style, with the first letter always being a lowercase letter.

Here are some more guidelines:

* Accessors for class variables should always be prefixed with "`get`" or "`set`".
* Methods that return data from some other source should be prefixed with "`fetch`"
* Methods that check for some flag should be prefixed with "`is`".

## Variable Names

Variable names should generally all be lowercase with words being camelCase. Verbosity is encouraged. Short names like `$i` or `$x` are permitted only in very small blocks of code.

Examples: `$userInfo`, `$permissions`, `$style`

## Constants

All constants must be defined within a class for namespacing. Constants must be all uppercase, with words separated by underscores.

Examples: `CHARS_NUM`, `BOUNDARY_ABOVE`


Coding Style
================================================================================

## Strings

### String Literals

String literals (no variables) should always be defined using single-quotes. The exception to this rule is when single-quotes will be used within the string, you should use double-quotes instead to remove the necessity of hard to read escapes.

    $str = 'This is a string literal';
    $sql = "SELECT id FROM settings WHERE name='PayPal'";

### Variable Substitution

When variables are to be substituted within a string, double quotes must be used. Simple variable names can be inserted into the string bare. Complex variable names, such as arrays, must always be concatenated. 

    $str = "Hello $name!";
    $str = 'Welcome back, ' . $user_info['name'] . '!';

## Arrays

When declaring large arrays, you should split the values into multiple lines. There should be a newline after the array opening, and then one level of indentation for each line of values, and then another newline before the close of the array declaration:

    $big_array = array(
        1, 2, 3, 4, 5,
        6, 7, 8, 9, 10,
        11, 12, 13, 14, 15,
    );

When defining large arrays like this, it is encouraged to leave a trailing comma to make it easy to add items later.

For hashes, there should be one pair of key/value per line:

## Classes

### Declaration

Class names must follow the naming conventions outlined above.

There must always be only one class per file.

The brace must be written on a new line under the class name.

The namespace must also be defined at the beginning of the file.

	namespace payments\modules;

    class Application
    {

    }

### Member Variables

Variables must be named according to the naming conventions outlined above.

All variables must be declared at the top of the class, above any methods.

All variables must declare visibility (`private, protected, public`). Public variables are allowed but discouraged in favor of accessor methods.

All variables must be documented of this use.

There must be one blank line between variable declarations.

All member variables must be documented, even `protected` or `private`. At a minimum, there must be at least the `@var` hint.

    /**
     * Cache object used when caching results.
     * @var MyCacheObj
     */
    protected $_cache;

## Functions and Methods

### Method Declaration

Methods must be named according to the naming conventions outlined above.

Methods must declare visibility.

The `static` keyword must always come after the visibility keyword.

Code within functions must be indented one level.

All methods, even private, must be documented.

There must be three blank lines between method declarations. This provides visual separation and makes the file easier to scan.

### Function and Method Invocation

There must be no space between the function name and opening parenthesis.

Each parameter is to be separated by a comma and a space.

Long lines may be split up in a similar manner that arrays are, described above.

    some_method(
        1, 2, 3, 'String',
        'Another', $fetcher
    );

## Control Statements

### If, Else and Elseif

These statements must have a single space between the keyword and the opening parenthesis of the condition, and a single space after the closing parenthesis.

The opening brace is written on the same line as the conditional statement.

Code within the braces is to be indented one level.

With `else` and `elseif` clauses, the statement must be written on the same line as the previous statements closing brace.

    if ($a == 1) {
        $a = 2;
    } elseif ($a == 2) {
        $a = 3;
    } else {
        $a += 5;
    }

All statement must include the curly braces. The only exception to this rule is when there is a single, short statement that is to be written on the same line:

    while (true) {
        if (!$current->isEnabled()) continue;
    }

### Switch

The switch statement must have a single space between the keyword and the opening parenthesis. The brace is to be inserted on the same line, with a single space between it and the closing parenthesis.

The `case` statements are to be indented one level. The content of each case is again indented one level, with the `break` keyword being in the same level.

    switch ($something) {
        case 1:
            // ...
            break;

        case 2:
            // ...
            break;

        default:
            // ...
            break;
    }


Inline Documentation
================================================================================

## Files

All files must contain a docblock at the top of the file that contains these tags at a minimum:

    /**
     * Description about the file.
     *
     * @package Payment Methods
	 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
     * @author Your Name <your@email.com>
     */

## Classes

All classes must have a docblock to describe what it does. Example usage is encouraged.

    /**
     * Description for the class.
     */

## Methods

All methods must be documented, even `protected` or `private`. Documentation must include:

* A description of the function.
* * Example usage is encouraged.
* All of the arguments
* All possible return values
* If a method may throw an exception, then all known exception classes must be documented.

Example:

    /**
     * Adds two integers
     *
     * @param int $a First integer
     * @param int $b Second integer
     * @return int
     * @throws Some_Exception Thrown when either number if 42
     */
     public function add($a, $b)
     {
         // ...
     }



