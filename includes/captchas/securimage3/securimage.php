<?php
/**
 * Project:  Securimage: A PHP class dealing with CAPTCHA images, audio, and validation
 * File:     securimage.php
 * Copyright (c) 2016, Drew Phillips
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *  - Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * Any modifications to the library should be indicated clearly in the source code
 * to inform users that the changes are not a part of the original software.
 * If you found this script useful, please take a quick moment to rate it.
 * http://www.hotscripts.com/rate/49400.html  Thanks.
 * @link http://www.phpcaptcha.org Securimage PHP CAPTCHA
 * @link http://www.phpcaptcha.org/latest.zip Download Latest Version
 * @link http://www.phpcaptcha.org/Securimage_Docs/ Online Documentation
 * @copyright 2016 Drew Phillips
 * @author Drew Phillips <drew@drew-phillips.com>
 * @version 3.6.4 (Mar 3, 2016)
 * @package Securimage
 */

/**
 * ChangeLog
 * 3.6.4
 * - Fix XSS vulnerability in example_form.ajax.php (Discovered by RedTeam. advisory rt-sa-2016-002)
 * - Update example_form.ajax.php to use Securimage::getCaptchaHtml()
 * 3.6.3
 * - Add support for multibyte wordlist files
 * - Fix code generation issues with UTF-8 charsets
 * - Add parameter to getCaptchaHtml() method to control display components of captcha HTML
 * - Fix database audio storage issue with multiple namespaces
 * 3.6.2
 * - Support HTTP range requests with audio playback (iOS requirement)
 * - Add optional config.inc.php for storing global configuration settings
 * 3.6.1
 * - Fix copyElement bug in securimage.js for IE Flash fallback
 * 3.6
 * - Implement CAPTCHA audio using HTML5 <audio> with optional Flash fallback
 * - Support MP3 audio using LAME MP3 Encoder (Internet Explorer 9+ does not support WAV format in <audio> tags)
 * - Add getCaptchaHtml() options to support full framework integration (ruifil)
 * 3.5.4
 * - Fix email validation code in example form files
 * - Fix backslashes in getCaptchaHtml for img attribute on Windows systems
 * 3.5.3
 * - Add options for audio button to getCaptchaHtml(), fix urlencoding of flash parameters that was breaking button
 * 3.5.2
 * - Add Securimage::getCaptchaHtml() for getting automatically generated captcha html code
 * - Option for using SoX to add effects to captcha audio to make identification by neural networks more difficult
 * - Add setNamespace() method
 * - Add getTimeToSolve() method
 * - Add session_status() check so session still starts if one had previously been opened and closed
 * - Add .htaccess file to audio directory to deny access; update audio files
 * - Option to skip checking of database tables during connection
 * - Add composer.json to package, submit to packagist
 * - Add font_ratio variable to determine size of font (github.com/wilkor)
 * - Add hint if sqlite3 database is not writeable.  Improve database error handling, add example database options to securimage_play.php
 * - Fixed issue regarding database storage and math captcha breaking audio output (github.com/SoftwareAndOutsourcing)
 * 3.5.1
 * - Fix XSS vulnerability in example_form.php (discovered by Gjoko Krstic - <gjoko@zeroscience.mk>)
 * 3.5
 * - Release new version
 * - MB string support for charlist
 * - Modify audio file path to use language directories
 * - Changed default captcha appearance
 * 3.2RC4
 * - Add MySQL, PostgreSQL, and SQLite3 support for database storage
 * - Deprecate "use_sqlite_db" option and remove SQLite2/sqlite_* functions
 * - Add new captcha type that displays 2 dictionary words on one image
 * - Update examples
 * 3.2RC3
 * - Fix canSendHeaders() check which was breaking if a PHP startup error was issued
 * 3.2RC2
 * - Add error handler (https://github.com/dapphp/securimage/issues/15)
 * - Fix flash examples to use the correct value name for audio parameter
 * 3.2RC1
 * - New audio captcha code.  Faster, fully dynamic audio, full WAV support
 *   (Paul Voegler, Drew Phillips) <http://voegler.eu/pub/audio>
 * - New Flash audio streaming button.  User defined image and size supported
 * - Additional options for customizing captcha (noise_level, send_headers,
 *   no_exit, no_session, display_value
 * - Add captcha ID support.  Uses sqlite and unique captcha IDs to track captchas,
 *   no session used
 * - Add static methods for creating and validating captcha by ID
 * - Automatic clearing of old codes from SQLite database
 * 3.0.3Beta
 * - Add improved mixing function to WavFile class (Paul Voegler)
 * - Improve performance and security of captcha audio (Paul Voegler, Drew Phillips)
 * - Add option to use random file as background noise in captcha audio
 * - Add new securimage options for audio files
 * 3.0.2Beta
 * - Fix issue with session variables when upgrading from 2.0 - 3.0
 * - Improve audio captcha, switch to use WavFile class, make mathematical captcha audio work
 * 3.0.1
 * - Bugfix: removed use of deprecated variable in addSignature method that would cause errors with display_errors on
 * 3.0
 * - Rewrite class using PHP5 OOP
 * - Remove support for GD fonts, require FreeType
 * - Remove support for multi-color codes
 * - Add option to make codes case-sensitive
 * - Add namespaces to support multiple captchas on a single page or page specific captchas
 * - Add option to show simple math problems instead of codes
 * - Remove support for mp3 files due to vulnerability in decoding mp3 audio files
 * - Create new flash file to stream wav files instead of mp3
 * - Changed to BSD license
 * 2.0.2
 * - Fix pathing to make integration into libraries easier (Nathan Phillip Brink ohnobinki@ohnopublishing.net)
 * 2.0.1
 * - Add support for browsers with cookies disabled (requires php5, sqlite) maps users to md5 hashed ip addresses and md5 hashed codes for security
 * - Add fallback to gd fonts if ttf support is not enabled or font file not found (Mike Challis http://www.642weather.com/weather/scripts.php)
 * - Check for previous definition of image type constants (Mike Challis)
 * - Fix mime type settings for audio output
 * - Fixed color allocation issues with multiple colors and background images, consolidate allocation to one function
 * - Ability to let codes expire after a given length of time
 * - Allow HTML color codes to be passed to Securimage_Color (suggested by Mike Challis)
 * 2.0.0
 * - Add mathematical distortion to characters (using code from HKCaptcha)
 * - Improved session support
 * - Added Securimage_Color class for easier color definitions
 * - Add distortion to audio output to prevent binary comparison attack (proposed by Sven "SavageTiger" Hagemann [insecurity.nl])
 * - Flash button to stream mp3 audio (Douglas Walsh www.douglaswalsh.net)
 * - Audio output is mp3 format by default
 * - Change font to AlteHaasGrotesk by yann le coroller
 * - Some code cleanup
 * 1.0.4 (unreleased)
 * - Ability to output audible codes in mp3 format to stream from flash
 * 1.0.3.1
 * - Error reading from wordlist in some cases caused words to be cut off 1 letter short
 * 1.0.3
 * - Removed shadow_text from code which could cause an undefined property error due to removal from previous version
 * 1.0.2
 * - Audible CAPTCHA Code wav files
 * - Create codes from a word list instead of random strings
 * 1.0
 * - Added the ability to use a selected character set, rather than a-z0-9 only.
 * - Added the multi-color text option to use different colors for each letter.
 * - Switched to automatic session handling instead of using files for code storage
 * - Added GD Font support if ttf support is not available.  Can use internal GD fonts or load new ones.
 * - Added the ability to set line thickness
 * - Added option for drawing arced lines over letters
 * - Added ability to choose image type for output
 */

/**
 * Securimage CAPTCHA Class.
 * A class for creating and validating secure CAPTCHA images and audio.
 * The class contains many options regarding appearance, security, storage of
 * captcha data and image/audio generation options.
 * @version 3.5.2
 * @package Securimage
 * @subpackage classes
 * @author Drew Phillips <drew@drew-phillips.com>
 */
require_once __DIR__."../../../../config.php";
class Securimage {
    // All of the public variables below are securimage options
    // They can be passed as an array to the Securimage constructor, set below,
    // or set from securimage_show.php and securimage_play.php
    /**
     * Constant for rendering captcha as a JPEG image
     * @var int
     */
    const SI_IMAGE_JPEG = 1;
    /**
     * Constant for rendering captcha as a PNG image (default)
     * @var int
     */
    const SI_IMAGE_PNG = 2;
    /**
     * Constant for rendering captcha as a GIF image
     * @var int
     */
    const SI_IMAGE_GIF = 3;
    /**
     * Constant for generating a normal alphanumeric captcha based on the
     * character set
     * @see Securimage::$charset charset property
     * @var int
     */
    const SI_CAPTCHA_STRING = 0;
    /**
     * Constant for generating a captcha consisting of a simple math problem
     * @var int
     */
    const SI_CAPTCHA_MATHEMATIC = 1;
    /**
     * Constant for generating a word based captcha using 2 words from a list
     * @var int
     */
    const SI_CAPTCHA_WORDS = 2;
    /**
     * MySQL option identifier for database storage option
     * @var string
     */
    const SI_DRIVER_MYSQL = 'mysql';
    /**
     * PostgreSQL option identifier for database storage option
     * @var string
     */
    const SI_DRIVER_PGSQL = 'pgsql';
    /**
     * SQLite option identifier for database storage option
     * @var string
     */
    const SI_DRIVER_SQLITE3 = 'sqlite';

    /**
     * getCaptchaHtml() display constant for HTML Captcha Image
     * @var integer
     */
    const HTML_IMG = 1;
    /**
     * getCaptchaHtml() display constant for HTML5 Audio code
     * @var integer
     */
    const HTML_AUDIO = 2;
    /**
     * getCaptchaHtml() display constant for Captcha Input text box
     * @var integer
     */
    const HTML_INPUT = 4;
    /**
     * getCaptchaHtml() display constant for Captcha Text HTML label
     * @var integer
     */
    const HTML_INPUT_LABEL = 8;
    /**
     * getCaptchaHtml() display constant for HTML Refresh button
     * @var integer
     */
    const HTML_ICON_REFRESH = 16;
    /**
     * getCaptchaHtml() display constant for all HTML elements (default)
     * @var integer
     */
    const HTML_ALL = 0xffffffff;

    /*%*********************************************************************%*/
    // Properties
    /**
     * The path to the lame (mp3 encoder) binary on your system
     * Static so that Securimage::getCaptchaHtml() has access to this value.
     * @since 3.6
     * @var string
     */
    public static $lame_binary_path = '/usr/bin/lame';
    /**
     * Captcha ID if using static captcha
     * @var string Unique captcha id
     */
    protected static $_captchaId = NULL;
    /**
     * The width of the captcha image
     * @var int
     */
    public $image_width = 215;
    /**
     * The height of the captcha image
     * @var int
     */
    public $image_height = 80;
    /**
     * Font size is calculated by image height and this ratio.  Leave blank for
     * default ratio of 0.4.
     * Valid range: 0.1 - 0.99.
     * Depending on image_width, values > 0.6 are probably too large and
     * values < 0.3 are too small.
     * @var float
     */
    public $font_ratio;
    /**
     * The type of the image, default = png
     * @see Securimage::SI_IMAGE_PNG SI_IMAGE_PNG
     * @see Securimage::SI_IMAGE_JPEG SI_IMAGE_JPEG
     * @see Securimage::SI_IMAGE_GIF SI_IMAGE_GIF
     * @var int
     */
    public $image_type = self::SI_IMAGE_PNG;
    /**
     * The background color of the captcha
     * @var Securimage_Color|string
     */
    public $image_bg_color = '#ffffff';
    /**
     * The color of the captcha text
     * @var Securimage_Color|string
     */
    public $text_color = '#707070';
    /**
     * The color of the lines over the captcha
     * @var Securimage_Color|string
     */
    public $line_color = '#707070';
    /**
     * The color of the noise that is drawn
     * @var Securimage_Color|string
     */
    public $noise_color = '#707070';
    /**
     * How transparent to make the text.
     * 0 = completely opaque, 100 = invisible
     * @var int
     */
    public $text_transparency_percentage = 20;
    /**
     * Whether or not to draw the text transparently.
     * true = use transparency, false = no transparency
     * @var bool
     */
    public $use_transparent_text = TRUE;
    /**
     * The length of the captcha code
     * @var int
     */
    public $code_length = 6;
    /**
     * Whether the captcha should be case sensitive or not.
     * Not recommended, use only for maximum protection.
     * @var bool
     */
    public $case_sensitive = FALSE;
    /**
     * The character set to use for generating the captcha code
     * @var string
     */
    public $charset = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz23456789';
    /**
     * How long in seconds a captcha remains valid, after this time it will be
     * considered incorrect.
     * @var int
     */
    public $expiry_time = 900;
    /**
     * The session name securimage should use.
     * Only use if your application uses a custom session name (e.g. Joomla).
     * It is recommended to set this value here so it is used by all securimage
     * scripts (i.e. securimage_show.php)
     * @var string
     */
    public $session_name;
    /**
     * true to use the wordlist file, false to generate random captcha codes
     * @var bool
     */
    public $use_wordlist = FALSE;
    /**
     * The level of distortion.
     * 0.75 = normal, 1.0 = very high distortion
     * @var double
     */
    public $perturbation = 0.85;
    /**
     * How many lines to draw over the captcha code to increase security
     * @var int
     */
    public $num_lines = 5;
    /**
     * The level of noise (random dots) to place on the image, 0-10
     * @var int
     */
    public $noise_level = 2;
    /**
     * The signature text to draw on the bottom corner of the image
     * @var string
     */
    public $image_signature = '';
    /**
     * The color of the signature text
     * @var Securimage_Color|string
     */
    public $signature_color = '#707070';
    /**
     * The path to the ttf font file to use for the signature text.
     * Defaults to $ttf_file (AHGBold.ttf)
     * @see Securimage::$ttf_file
     * @var string
     */
    public $signature_font;
    /**
     * No longer used.
     * Use an SQLite database to store data (for users that do not support cookies)
     * @var bool
     * @see Securimage::$database_driver database_driver property
     * @deprecated 3.2RC4
     */
    public $use_sqlite_db = FALSE;
    /**
     * Use a database backend for code storage.
     * Provides a fallback to users with cookies disabled.
     * Required when using captcha IDs.
     * @see Securimage::$database_driver
     * @var bool
     */
    public $use_database = FALSE;
    /**
     * Whether or not to skip checking if Securimage tables exist when using a
     * database.
     * Turn this to true once database functionality is working to improve
     * performance.
     * @var bool true to not check if captcha_codes tables are set up, false
     * to check (and create if necessary)
     */
    public $skip_table_check = FALSE;
    /**
     * Database driver to use for database support.
     * Allowable values: *mysql*, *pgsql*, *sqlite*.
     * Default: sqlite
     * @var string
     */
    public $database_driver = self::SI_DRIVER_SQLITE3;
    /**
     * Database host to connect to when using mysql or postgres
     * On Linux use "localhost" for Unix domain socket, otherwise uses TCP/IP
     * Does not apply to SQLite
     * @var string
     */
    public $database_host = 'localhost';
    /**
     * Database username for connection (mysql, postgres only)
     * Default is an empty string
     * @var string
     */
    public $database_user = '';
    /**
     * Database password for connection (mysql, postgres only)
     * Default is empty string
     * @var string
     */
    public $database_pass = '';
    /**
     * Name of the database to select (mysql, postgres only)
     * @see Securimage::$database_file for SQLite
     * @var string
     */
    public $database_name = '';
    /**
     * Database table where captcha codes are stored
     * Note: Securimage will attempt to create this table for you if it does
     * not exist.  If the table cannot be created, an E_USER_WARNING is emitted
     * @var string
     */
    public $database_table = 'captcha_codes'; // or self::SI_CAPTCHA_MATHEMATIC, or self::SI_CAPTCHA_WORDS;
    /**
     * Fully qualified path to the database file when using SQLite3.
     * This value is only used when $database_driver == sqlite and does
     * not apply when no database is used, or when using MySQL or PostgreSQL.
     * On *nix, file must have permissions of 0666.
     * **Make sure the directory containing this file is NOT web accessible**
     * @var string
     */
    public $database_file;
    /**
     * The type of captcha to create.
     * Either alphanumeric based on *charset*, a simple math problem, or an
     * image consisting of 2 words from the word list.
     * @see Securimage::SI_CAPTCHA_STRING SI_CAPTCHA_STRING
     * @see Securimage::SI_CAPTCHA_MATHEMATIC SI_CAPTCHA_MATHEMATIC
     * @see Securimage::SI_CAPTCHA_WORDS SI_CAPTCHA_WORDS
     * @see Securimage::$charset charset property
     * @see Securimage::$wordlist_file wordlist_file property
     * @var int
     */
    public $captcha_type = self::SI_CAPTCHA_STRING;
    /**
     * The captcha namespace used for having multiple captchas on a page or
     * to separate captchas from differen forms on your site.
     * Example:
     *     <?php
     *     // use <img src="securimage_show.php?namespace=contact_form">
     *     // or manually in securimage_show.php
     *     $img->setNamespace('contact_form');
     *
     *     // in form validator
     *     $img->setNamespace('contact_form');
     *     if ($img->check($code) == true) {
     *         echo "Valid!";
     *     }
     *     ?>
     * @var string
     */
    public $namespace;
    /**
     * The TTF font file to use to draw the captcha code.
     * Leave blank for default font AHGBold.ttf
     * @var string
     */
    public $ttf_file;
    /**
     * The path to the wordlist file to use.
     * Leave blank for default words/words.txt
     * @var string
     */
    public $wordlist_file;
    /**
     * Character encoding of the wordlist file.
     * Requires PHP Multibyte String (mbstring) support.
     * Allows word list to contain characters other than US-ASCII (requires compatible TTF font).
     * @var string The character encoding (e.g. UTF-8, UTF-7, EUC-JP, GB2312)
     * @see http://php.net/manual/en/mbstring.supported-encodings.php
     * @since 3.6.3
     */
    public $wordlist_file_encoding = NULL;
    /**
     * The directory to scan for background images, if set a random background
     * will be chosen from this folder
     * @var string
     */
    public $background_directory;
    /**
     * No longer used
     * The path to the SQLite database file to use
     * @deprecated 3.2RC4
     * @see Securimage::$database_file database_file property
     * @var string
     */
    public $sqlite_database;
    /**
     * The path to the audio files to be used for audio captchas.
     * Can also be set in securimage_play.php
     * Example:
     *     <?php
     *     $img->audio_path = '/home/yoursite/public_html/securimage/audio/en/';
     *     ?>
     * @var string
     */
    public $audio_path;
    /**
     * Use SoX (The Swiss Army knife of audio manipulation) for audio effects
     * and processing.
     * Using SoX should make it more difficult for bots to solve audio captchas
     * @see Securimage::$sox_binary_path sox_binary_path property
     * @var bool true to use SoX, false to use PHP
     */
    public $audio_use_sox = FALSE;
    /**
     * The path to the SoX binary on your system
     * @var string
     */
    public $sox_binary_path = '/usr/bin/sox';
    /**
     * The path to the directory containing audio files that will be selected
     * randomly and mixed with the captcha audio.
     * @var string
     */
    public $audio_noise_path;
    /**
     * Whether or not to mix background noise files into captcha audio
     * Mixing random background audio with noise can help improve security of
     * audio captcha.
     * Default: securimage/audio/noise
     * @since 3.0.3
     * @see Securimage::$audio_noise_path audio_noise_path property
     * @var bool true = mix, false = no
     */
    public $audio_use_noise;
    /**
     * The method and threshold (or gain factor) used to normalize the mixing
     * with background noise.
     * See http://www.voegler.eu/pub/audio/ for more information.
     * Default: 0.6
     * Valid:
     *     >= 1
     *     Normalize by multiplying by the threshold (boost - positive gain).
     *     A value of 1 in effect means no normalization (and results in clipping).
     *     <= -1
     *     Normalize by dividing by the the absolute value of threshold (attenuate - negative gain).
     *     A factor of 2 (-2) is about 6dB reduction in volume.
     *     [0, 1)  (open inverval - not including 1)
     *     The threshold above which amplitudes are comressed logarithmically.
     *     e.g. 0.6 to leave amplitudes up to 60% "as is" and compressabove.
     *
     *     (-1, 0) (open inverval - not including -1 and 0)
     *     The threshold above which amplitudes are comressed linearly.
     *     e.g. -0.6 to leave amplitudes up to 60% "as is" and compress above.
     * @since 3.0.4
     * @var float
     */
    public $audio_mix_normalization = 0.8;
    /**
     * Whether or not to degrade audio by introducing random noise.
     * Current research shows this may not increase the security of audible
     * captchas.
     * Default: true
     * @since 3.0.3
     * @var bool
     */
    public $degrade_audio;
    /**
     * Minimum delay to insert between captcha audio letters in milliseconds
     * @since 3.0.3
     * @var float
     */
    public $audio_gap_min = 0;
    /**
     * Maximum delay to insert between captcha audio letters in milliseconds
     * @since 3.0.3
     * @var float
     */
    public $audio_gap_max = 3000;
    /**
     * Absolute path to securimage directory.
     * This is calculated at runtime
     * @var string
     */
    public $securimage_path = NULL;
    /**
     * Alternate text to draw as the captcha image text
     * A value that can be passed to the constructor that can be used to
     * generate a captcha image with a given value.
     * This value does not get stored in the session or database and is only
     * used when calling Securimage::show().
     * If a display_value was passed to the constructor and the captcha image
     * is generated, the display_value will be used as the string to draw on
     * the captcha image.
     * Used only if captcha codes are generated and managed by a 3rd party
     * app/library
     * @var string Captcha code value to display on the image
     */
    public $display_value;
    /**
     * The GD image resource of the captcha image
     * @var resource
     */
    protected $im;
    /**
     * A temporary GD image resource of the captcha image for distortion
     * @var resource
     */
    protected $tmpimg;
    /**
     * The background image GD resource
     * @var string
     */
    protected $bgimg;
    /**
     * Scale factor for magnification of distorted captcha image
     * @var int
     */
    protected $iscale = 5;
    /**
     * The captcha challenge value.
     * Either the case-sensitive/insensitive word captcha, or the solution to
     * the math captcha.
     * @var string|bool Captcha challenge value
     */
    protected $code;
    /**
     * The display value of the captcha to draw on the image
     * Either the word captcha or the math equation to present to the user
     * @var string Captcha display value to draw on the image
     */
    protected $code_display;
    /**
     * Captcha code supplied by user [set from Securimage::check()]
     * @var string
     */
    protected $captcha_code;
    /**
     * Time (in seconds) that the captcha was solved in (correctly or incorrectly).
     * This is from the time of code creation, to when validation was attempted.
     * @var int
     */
    protected $_timeToSolve = 0;
    /**
     * Flag that can be specified telling securimage not to call exit after
     * generating a captcha image or audio file
     * @var bool If true, script will not terminate; if false script will terminate (default)
     */
    protected $no_exit;
    /**
     * Flag indicating whether or not a PHP session should be started and used
     * @var bool If true, no session will be started; if false, session will be started and used to store data (default)
     */
    protected $no_session;
    /**
     * Flag indicating whether or not HTTP headers will be sent when outputting
     * captcha image/audio
     * @var bool If true (default) headers will be sent, if false, no headers are sent
     */
    protected $send_headers;
    /**
     * PDO connection when a database is used
     * @var PDO|bool
     */
    protected $pdo_conn;
    /**
     * The GD color for the background color
     * @var int
     */
    protected $gdbgcolor;
    /**
     * The GD color for the text color
     * @var int
     */
    protected $gdtextcolor;
    /**
     * The GD color for the line color
     * @var int
     */
    protected $gdlinecolor;
    /**
     * The GD color for the signature text color
     * @var int
     */
    protected $gdsignaturecolor;

    /**
     * Create a new securimage object, pass options to set in the constructor.
     * The object can then be used to display a captcha, play an audible captcha, or validate a submission.
     * @param array $options Options to initialize the class.  May be any class property.
     *     <?php
     *     $options = array(
     *         'text_color' => new Securimage_Color('#013020'),
     *         'code_length' => 5,
     *         'num_lines' => 5,
     *         'noise_level' => 3,
     *         'font_file' => Securimage::getPath() . '/custom.ttf'
     *     );
     *     $img = new Securimage($options);
     *     ?>
     */
    public function __construct($options = array()) {
        $this->session_name = COOKIE_PREFIX.'session';
        $this->securimage_path = dirname(__FILE__);

        if (!is_array($options)) {
            trigger_error('$options passed to Securimage::__construct() must be an array. '.gettype($options).' given', E_USER_WARNING);

            $options = array();
        }

        // check for and load settings from custom config file
        if (file_exists(dirname(__FILE__).'/config.inc.php')) {
            $settings = include dirname(__FILE__).'/config.inc.php';

            if (is_array($settings)) {
                $options = array_merge($settings, $options);
            }
        }

        if (is_array($options) && sizeof($options) > 0) {
            foreach ($options as $prop => $val) {
                if ($prop == 'captchaId') {
                    Securimage::$_captchaId = $val;
                    $this->use_database = TRUE;
                } else {
                    if ($prop == 'use_sqlite_db') {
                        trigger_error("The use_sqlite_db option is deprecated, use 'use_database' instead", E_USER_NOTICE);
                    } else {
                        $this->$prop = $val;
                    }
                }
            }
        }

        $this->image_bg_color = $this->initColor($this->image_bg_color, '#ffffff');
        $this->text_color = $this->initColor($this->text_color, '#616161');
        $this->line_color = $this->initColor($this->line_color, '#616161');
        $this->noise_color = $this->initColor($this->noise_color, '#616161');
        $this->signature_color = $this->initColor($this->signature_color, '#616161');

        if (is_null($this->ttf_file)) {
            $this->ttf_file = $this->securimage_path.'/AHGBold.ttf';
        }

        $this->signature_font = $this->ttf_file;

        if (is_null($this->wordlist_file)) {
            $this->wordlist_file = $this->securimage_path.'/words/words.txt';
        }
        if (is_null($this->database_file)) {
            $this->database_file = $this->securimage_path.'/database/securimage.sq3';
        }
        if (is_null($this->audio_path)) {
            $this->audio_path = $this->securimage_path.'/audio/';
        }
        if (is_null($this->audio_noise_path)) {
            $this->audio_noise_path = $this->securimage_path.'/audio/noise/';
        }
        if (is_null($this->audio_use_noise)) {
            $this->audio_use_noise = FALSE;
        }
        if (is_null($this->degrade_audio)) {
            $this->degrade_audio = TRUE;
        }
        if (is_null($this->code_length) || (int)$this->code_length < 1) {
            $this->code_length = 6;
        }
        if (is_null($this->perturbation) || !is_numeric($this->perturbation)) {
            $this->perturbation = 0.75;
        }
        if (is_null($this->namespace) || !is_string($this->namespace)) {
            $this->namespace = 'default';
        }
        if (is_null($this->no_exit)) {
            $this->no_exit = FALSE;
        }
        if (is_null($this->no_session)) {
            $this->no_session = FALSE;
        }
        if (is_null($this->send_headers)) {
            $this->send_headers = TRUE;
        }

        if ($this->no_session != TRUE) {
            // Initialize session or attach to existing
            if ( session_id() == '' || (function_exists('session_status') && PHP_SESSION_NONE == session_status()) ) { // no session has been started yet (or it was previousy closed), which is needed for validation
                if (!is_null($this->session_name) && trim($this->session_name) != '') {
                    session_name(trim($this->session_name)); // set session name if provided
                }
                session_start();
            }
        }
    }

    /**
     * Convert an html color code to a Securimage_Color
     * @param string                  $color
     * @param Securimage_Color|string $default The defalt color to use if $color is invalid
     */
    protected function initColor($color, $default) {
        if ($color == NULL) {
            return new Securimage_Color($default);
        } else {
            if (is_string($color)) {
                try {
                    return new Securimage_Color($color);
                } catch (Exception $e) {
                    return new Securimage_Color($default);
                }
            } else {
                if (is_array($color) && sizeof($color) == 3) {
                    return new Securimage_Color($color[0], $color[1], $color[2]);
                } else {
                    return new Securimage_Color($default);
                }
            }
        }
    }

    /**
     * Return the absolute path to the Securimage directory.
     * @return string The path to the securimage base directory
     */
    public static function getPath() {
        return dirname(__FILE__);
    }

    /**
     * Validate a captcha code input against a captcha ID
     * @param string $id The captcha ID to check
     * @param string $value The captcha value supplied by the user
     * @param array  $options Array of options to construct Securimage with.
     * Options must include database options if they are not set in securimage.php
     * @see Securimage::$database_driver
     * @return bool true if the code was valid for the given captcha ID, false if not or if database failed to open
     */
    public static function checkByCaptchaId($id, $value, array $options = array()) {
        $opts = array(
            'captchaId' => $id,
            'no_session' => TRUE,
            'use_database' => TRUE
        );

        if (sizeof($options) > 0) {
            $opts = array_merge($options, $opts);
        }

        $si = new self($opts);

        if ($si->openDatabase()) {
            $code = $si->getCodeFromDatabase();

            if (is_array($code)) {
                $si->code = $code['code'];
                $si->code_display = $code['code_disp'];
            }

            if ($si->check($value)) {
                $si->clearCodeFromDatabase();

                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Opens a connection to the configured database.
     * @see Securimage::$use_database Use database
     * @see Securimage::$database_driver Database driver
     * @see Securimage::$pdo_conn pdo_conn
     * @return bool true if the database connection was successful, false if not
     */
    protected function openDatabase() {
        $this->pdo_conn = FALSE;

        if ($this->use_database) {
            $pdo_extension = 'PDO_'.strtoupper($this->database_driver);

            if (!extension_loaded($pdo_extension)) {
                trigger_error("Database support is turned on in Securimage, but the chosen extension $pdo_extension is not loaded in PHP.",
                              E_USER_WARNING);

                return FALSE;
            }
        }

        if ($this->database_driver == self::SI_DRIVER_SQLITE3) {
            if (!file_exists($this->database_file)) {
                $fp = fopen($this->database_file, 'w+');
                if (!$fp) {
                    $err = error_get_last();
                    trigger_error("Securimage failed to create SQLite3 database file '{$this->database_file}'. Reason: {$err['message']}",
                                  E_USER_WARNING);

                    return FALSE;
                }
                fclose($fp);
                chmod($this->database_file, 0666);
            } else {
                if (!is_writeable($this->database_file)) {
                    trigger_error("Securimage does not have read/write access to database file '{$this->database_file}. Make sure permissions are 0666 and writeable by user '".get_current_user()."'",
                                  E_USER_WARNING);

                    return FALSE;
                }
            }
        }

        try {
            $dsn = $this->getDsn();

            $options = array();
            $this->pdo_conn = new PDO($dsn, $this->database_user, $this->database_pass, $options);
        } catch (PDOException $pdoex) {
            trigger_error("Database connection failed: ".$pdoex->getMessage(), E_USER_WARNING);

            return FALSE;
        } catch (Exception $ex) {
            trigger_error($ex->getMessage(), E_USER_WARNING);

            return FALSE;
        }

        try {
            if (!$this->skip_table_check && !$this->checkTablesExist()) {
                // create tables...
                $this->createDatabaseTables();
            }
        } catch (Exception $ex) {
            trigger_error($ex->getMessage(), E_USER_WARNING);
            $this->pdo_conn = FALSE;

            return FALSE;
        }

        if (mt_rand(0, 100) / 100.0 == 1.0) {
            $this->purgeOldCodesFromDatabase();
        }

        return $this->pdo_conn;
    }

    /**
     * Get the PDO DSN string for connecting to the database
     * @see Securimage::$database_driver Database driver
     * @throws Exception  If database specific options are not configured
     * @return string     The DSN for connecting to the database
     */
    protected function getDsn() {
        $dsn = sprintf('%s:', $this->database_driver);

        switch ($this->database_driver) {
            case self::SI_DRIVER_SQLITE3:
                $dsn .= $this->database_file;
                break;

            case self::SI_DRIVER_MYSQL:
            case self::SI_DRIVER_PGSQL:
                if (empty($this->database_host)) {
                    throw new Exception('Securimage::database_host is not set');
                } else {
                    if (empty($this->database_name)) {
                        throw new Exception('Securimage::database_name is not set');
                    }
                }

                $dsn .= sprintf('host=%s;dbname=%s',
                                $this->database_host,
                                $this->database_name);
                break;

        }

        return $dsn;
    }

    /**
     * Checks if the necessary database tables for storing captcha codes exist
     * @throws Exception If the table check failed for some reason
     * @return boolean true if the database do exist, false if not
     */
    protected function checkTablesExist() {
        $table = $this->pdo_conn->quote($this->database_table);

        switch ($this->database_driver) {
            case self::SI_DRIVER_SQLITE3:
                // query row count for sqlite, PRAGMA queries seem to return no
                // rowCount using PDO even if there are rows returned
                $query = "SELECT COUNT(id) FROM $table";
                break;

            case self::SI_DRIVER_MYSQL:
                $query = "SHOW TABLES LIKE $table";
                break;

            case self::SI_DRIVER_PGSQL:
                $query = "SELECT * FROM information_schema.columns WHERE table_name = $table;";
                break;
        }

        $result = $this->pdo_conn->query($query);

        if (!$result) {
            $err = $this->pdo_conn->errorInfo();

            if ($this->database_driver == self::SI_DRIVER_SQLITE3 &&
                $err[1] === 1 && strpos($err[2], 'no such table') !== FALSE
            ) {
                return FALSE;
            }

            throw new Exception("Failed to check tables: {$err[0]} - {$err[1]}: {$err[2]}");
        } else {
            if ($this->database_driver == self::SI_DRIVER_SQLITE3) {
                // successful here regardless of row count for sqlite
                return TRUE;
            } else {
                if ($result->rowCount() == 0) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        }
    }

    /**
     * Create the necessary databaes table for storing captcha codes.
     * Based on the database adapter used, the tables will created in the existing connection.
     * @see Securimage::$database_driver Database driver
     * @return boolean true if the tables were created, false if not
     */
    protected function createDatabaseTables() {
        $queries = array();

        switch ($this->database_driver) {
            case self::SI_DRIVER_SQLITE3:
                $queries[] = "CREATE TABLE \"{$this->database_table}\" (
                                id VARCHAR(40),
                                namespace VARCHAR(32) NOT NULL,
                                code VARCHAR(32) NOT NULL,
                                code_display VARCHAR(32) NOT NULL,
                                created INTEGER NOT NULL,
                                audio_data BLOB NULL,
                                PRIMARY KEY(id, namespace)
                              )";

                $queries[] = "CREATE INDEX ndx_created ON {$this->database_table} (created)";
                break;

            case self::SI_DRIVER_MYSQL:
                $queries[] = "CREATE TABLE `{$this->database_table}` (
                                `id` VARCHAR(40) NOT NULL,
                                `namespace` VARCHAR(32) NOT NULL,
                                `code` VARCHAR(32) NOT NULL,
                                `code_display` VARCHAR(32) NOT NULL,
                                `created` INT NOT NULL,
                                `audio_data` MEDIUMBLOB NULL,
                                PRIMARY KEY(id, namespace),
                                INDEX(created)
                              )";
                break;

            case self::SI_DRIVER_PGSQL:
                $queries[] = "CREATE TABLE {$this->database_table} (
                                id character varying(40) NOT NULL,
                                namespace character varying(32) NOT NULL,
                                code character varying(32) NOT NULL,
                                code_display character varying(32) NOT NULL,
                                created integer NOT NULL,
                                audio_data bytea NULL,
                                CONSTRAINT pkey_id_namespace PRIMARY KEY (id, namespace)
                              )";

                $queries[] = "CREATE INDEX ndx_created ON {$this->database_table} (created);";
                break;
        }

        $this->pdo_conn->beginTransaction();

        foreach ($queries as $query) {
            $result = $this->pdo_conn->query($query);

            if (!$result) {
                $err = $this->pdo_conn->errorInfo();
                trigger_error("Failed to create table.  {$err[1]}: {$err[2]}", E_USER_WARNING);
                $this->pdo_conn->rollBack();
                $this->pdo_conn = FALSE;

                return FALSE;
            }
        }

        $this->pdo_conn->commit();

        return TRUE;
    }

    /**
     * Deletes old (expired) codes from the database
     */
    protected function purgeOldCodesFromDatabase() {
        if ($this->use_database && $this->pdo_conn) {
            $now = time();
            $limit = (!is_numeric($this->expiry_time) || $this->expiry_time < 1) ? 86400 : $this->expiry_time;

            $query = sprintf("DELETE FROM %s WHERE %s - created > %s", $this->database_table, $now, $this->pdo_conn->quote("$limit", PDO::PARAM_INT));

            $result = $this->pdo_conn->query($query);
        }
    }

    /**
     * Retrieves a stored code from the database for based on the captchaId or
     * IP address if captcha ID not used.
     * @return string|array Empty string if no code was found or has expired,
     * otherwise returns array of code information.
     */
    protected function getCodeFromDatabase() {
        $code = '';

        if ($this->use_database == TRUE && $this->pdo_conn) {
            if (Securimage::$_captchaId !== NULL) {
                $query = "SELECT * FROM {$this->database_table} WHERE id = ?";
                $stmt = $this->pdo_conn->prepare($query);
                $result = $stmt->execute(array(Securimage::$_captchaId));
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
                $ns = $this->namespace;

                // ip is stored in id column when no captchaId
                $query = "SELECT * FROM {$this->database_table} WHERE id = ? AND namespace = ?";
                $stmt = $this->pdo_conn->prepare($query);
                $result = $stmt->execute(array($ip, $ns));
            }

            if (!$result) {
                $err = $this->pdo_conn->errorInfo();
                trigger_error("Failed to select code from database.  {$err[0]}: {$err[1]}", E_USER_WARNING);
            } else {
                if (($row = $stmt->fetch()) !== FALSE) {
                    if (FALSE == $this->isCodeExpired($row['created'])) {
                        if ($this->database_driver == self::SI_DRIVER_PGSQL && is_resource($row['audio_data'])) {
                            // pg bytea data returned as stream resource
                            $data = '';
                            while (!feof($row['audio_data'])) {
                                $data .= fgets($row['audio_data']);
                            }
                            $row['audio_data'] = $data;
                        }
                        $code = array(
                            'code' => $row['code'],
                            'code_disp' => $row['code_display'],
                            'time' => $row['created'],
                            'audio_data' => $row['audio_data'],
                        );
                    }
                }
            }
        }

        return $code;
    }

    /**
     * Checks to see if the captcha code has expired and can no longer be used.
     * @see Securimage::$expiry_time expiry_time
     * @param int $creation_time The Unix timestamp of when the captcha code was created
     * @return bool true if the code is expired, false if it is still valid
     */
    protected function isCodeExpired($creation_time) {
        $expired = TRUE;

        if (!is_numeric($this->expiry_time) || $this->expiry_time < 1) {
            $expired = FALSE;
        } else {
            if (time() - $creation_time < $this->expiry_time) {
                $expired = FALSE;
            }
        }

        return $expired;
    }

    /**
     * Checks a given code against the correct value from the session and/or database.
     * @param string $code The captcha code to check
     *     <?php
     *     $code = $_POST['code'];
     *     $img  = new Securimage();
     *     if ($img->check($code) == true) {
     *         $captcha_valid = true;
     *     } else {
     *         $captcha_valid = false;
     *     }
     *     ?>
     * @return bool true if the given code was correct, false if not.
     */
    private $code_entered = '';

    public function check($code) {
        $this->code_entered = $code;
        $this->validate();

        return $this->correct_code;
    }

    /**
     * Validate a code supplied by the user
     * Checks the entered code against the value stored in the session and/or database (if configured).  Handles case sensitivity.
     * Also removes the code from session/database if the code was entered correctly to prevent re-use attack.
     * This function does not return a value.
     * @see Securimage::$correct_code 'correct_code' property
     */
    private $correct_code = FALSE;

    protected function validate() {
        if (!is_string($this->code) || strlen($this->code) == 0) {
            $code = $this->getCode(TRUE);
            // returns stored code, or an empty string if no stored code was found
            // checks the session and database if enabled
        } else {
            $code = $this->code;
        }

        if (is_array($code)) {
            if (!empty($code)) {
                $ctime = $code['time'];
                $code = $code['code'];

                $this->_timeToSolve = time() - $ctime;
            } else {
                $code = '';
            }
        }

        if ($this->case_sensitive == FALSE && preg_match('/[A-Z]/', $code)) {
            // case sensitive was set from securimage_show.php but not in class
            // the code saved in the session has capitals so set case sensitive to true
            $this->case_sensitive = TRUE;
        }

        $code_entered = trim((($this->case_sensitive) ? $this->code_entered
            : strtolower($this->code_entered))
        );

        if ($code != '') {
            if (strpos($code, ' ') !== FALSE) {
                // for multi word captchas, remove more than once space from input
                $code_entered = preg_replace('/\s+/', ' ', $code_entered);
                $code_entered = strtolower($code_entered);
            }

            if ((string)$code === (string)$code_entered) {
                $this->correct_code = TRUE;
                if ($this->no_session != TRUE) {
                    $_SESSION['securimage_code_disp'] [$this->namespace] = '';
                    $_SESSION['securimage_code_value'][$this->namespace] = '';
                    $_SESSION['securimage_code_ctime'][$this->namespace] = '';
                    $_SESSION['securimage_code_audio'][$this->namespace] = '';
                }
                $this->clearCodeFromDatabase();
            }
        }
    }

    /**
     * Return the code from the session or database (if configured).  If none exists or was found, an empty string is returned.
     * @param bool $array true to receive an array containing the code and properties, false to receive just the code.
     * @param bool $returnExisting If true, and the class property *code* is set, it will be returned instead of getting the code from the session or database.
     * @return array|string Return is an array if $array = true, otherwise a string containing the code
     */
    public function getCode($array = FALSE, $returnExisting = FALSE) {
        $code = array();
        $time = 0;
        $disp = 'error';

        if ($returnExisting && strlen($this->code) > 0) {
            if ($array) {
                return array(
                    'code' => $this->code,
                    'display' => $this->code_display,
                    'code_display' => $this->code_display,
                    'time' => 0
                );
            } else {
                return $this->code;
            }
        }

        if ($this->no_session != TRUE) {
            if (isset($_SESSION['securimage_code_value'][$this->namespace]) &&
                trim($_SESSION['securimage_code_value'][$this->namespace]) != ''
            ) {
                if ($this->isCodeExpired(
                        $_SESSION['securimage_code_ctime'][$this->namespace]) == FALSE
                ) {
                    $code['code'] = $_SESSION['securimage_code_value'][$this->namespace];
                    $code['time'] = $_SESSION['securimage_code_ctime'][$this->namespace];
                    $code['display'] = $_SESSION['securimage_code_disp'] [$this->namespace];
                }
            }
        }

        if (empty($code) && $this->use_database) {
            // no code in session - may mean user has cookies turned off
            $this->openDatabase();
            $code = $this->getCodeFromDatabase();

            if (!empty($code)) {
                $code['display'] = $code['code_disp'];
                unset($code['code_disp']);
            }
        } else { /* no code stored in session or sqlite database, validation will fail */
        }

        if ($array == TRUE) {
            return $code;
        } else {
            return $code['code'];
        }
    }

    /**
     * Remove a stored code from the database based on captchaId or IP address.
     */
    protected function clearCodeFromDatabase() {
        if ($this->pdo_conn) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $ns = $this->pdo_conn->quote($this->namespace);
            $id = Securimage::$_captchaId;

            if (empty($id)) {
                $id = $ip; // if no captchaId set, IP address is captchaId.
            }

            $id = $this->pdo_conn->quote($id);

            $query = sprintf("DELETE FROM %s WHERE id = %s AND namespace = %s",
                             $this->database_table, $id, $ns);

            $result = $this->pdo_conn->query($query);
            if (!$result) {
                trigger_error("Failed to delete code from database.", E_USER_WARNING);
            }
        }
    }

    /**
     * Returns HTML code for displaying the captcha image, audio button, and form text input.
     * Options can be specified to modify the output of the HTML.  Accepted options:
     *     'securimage_path':
     *         Optional: The URI to where securimage is installed (e.g. /securimage)
     *     'show_image_url':
     *         Path to the securimage_show.php script (useful when integrating with a framework or moving outside the securimage directory)
     *         This will be passed as a urlencoded string to the <img> tag for outputting the captcha image
     *     'audio_play_url':
     *         Same as show_image_url, except this indicates the URL of the audio playback script
     *     'image_id':
     *          A string that sets the "id" attribute of the captcha image (default: captcha_image)
     *     'image_alt_text':
     *         The alt text of the captcha image (default: CAPTCHA Image)
     *     'show_audio_button':
     *         true/false  Whether or not to show the audio button (default: true)
     *     'disable_flash_fallback':)
     *         Allow only HTML5 audio and disable Flash fallback
     *     'show_refresh_button':
     *         true/false  Whether or not to show a button to refresh the image (default: true)
     *     'audio_icon_url':
     *         URL to the image used for showing the HTML5 audio icon
     *     'icon_size':
     *         Size (for both height & width) in pixels of the audio and refresh buttons
     *     'show_text_input':
     *         true/false  Whether or not to show the text input for the captcha (default: true)
     *     'refresh_alt_text':
     *         Alt text for the refresh image (default: Refresh Image)
     *     'refresh_title_text':
     *         Title text for the refresh image link (default: Refresh Image)
     *     'input_id':
     *         A string that sets the "id" attribute of the captcha text input (default: captcha_code)
     *     'input_name':
     *         A string that sets the "name" attribute of the captcha text input (default: same as input_id)
     *     'input_text':
     *         A string that sets the text of the label for the captcha text input (default: Type the text:)
     *     'input_attributes':
     *         An array of additional HTML tag attributes to pass to the text input tag (default: empty)
     *     'image_attributes':
     *         An array of additional HTML tag attributes to pass to the captcha image tag (default: empty)
     *     'error_html':
     *         Optional HTML markup to be shown above the text input field
     *     'namespace':
     *         The optional captcha namespace to use for showing the image and playing back the audio. Namespaces are for using multiple captchas on the same page.
     * @param array $options Array of options for modifying the HTML code.
     * @param int   $parts Securiage::HTML_* constant controlling what component of the captcha HTML to display
     * @return string  The generated HTML code for displaying the captcha
     */
    public static function getCaptchaHtml($options = array(), $parts = Securimage::HTML_ALL) {
        static $javascript_init = FALSE;

        if (!isset($options['securimage_path'])) {
            /*
             * Bug: https://www.php-fusion.co.uk/forum/viewthread.php?thread_id=38145&pid=201071#post_201071
             */
            //$docroot = (isset($_SERVER['DOCUMENT_ROOT'])) ? $_SERVER['DOCUMENT_ROOT'] : substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['SCRIPT_NAME']));
            //$docroot = realpath($docroot);
            //$sipath = dirname(__FILE__);
            //$securimage_path = str_replace($docroot, '', $sipath); // <---------- ../..//fusion9/includes/captchas/securimage3/images/audio_icon.png
            $securimage_path = INCLUDES.'captchas/securimage3';
        } else {
            $securimage_path = $options['securimage_path'];
        }

        $show_image_url = (isset($options['show_image_url'])) ? $options['show_image_url'] : NULL;
        $image_id = (isset($options['image_id'])) ? $options['image_id'] : 'captcha_image';
        $image_alt = (isset($options['image_alt_text'])) ? $options['image_alt_text'] : 'CAPTCHA Image';
        $show_audio_btn = (isset($options['show_audio_button'])) ? (bool)$options['show_audio_button'] : TRUE;
        $disable_flash_fbk = (isset($options['disable_flash_fallback'])) ? (bool)$options['disable_flash_fallback'] : FALSE;
        $show_refresh_btn = (isset($options['show_refresh_button'])) ? (bool)$options['show_refresh_button'] : TRUE;
        $refresh_icon_url = (isset($options['refresh_icon_url'])) ? $options['refresh_icon_url'] : NULL;
        $audio_but_bg_col = (isset($options['audio_button_bgcol'])) ? $options['audio_button_bgcol'] : '#ffffff';
        $audio_icon_url = (isset($options['audio_icon_url'])) ? $options['audio_icon_url'] : NULL;
        $loading_icon_url = (isset($options['loading_icon_url'])) ? $options['loading_icon_url'] : NULL;
        $icon_size = (isset($options['icon_size'])) ? $options['icon_size'] : 32;
        $audio_play_url = (isset($options['audio_play_url'])) ? $options['audio_play_url'] : NULL;
        $audio_swf_url = (isset($options['audio_swf_url'])) ? $options['audio_swf_url'] : NULL;
        $show_input = (isset($options['show_text_input'])) ? (bool)$options['show_text_input'] : TRUE;
        $refresh_alt = (isset($options['refresh_alt_text'])) ? $options['refresh_alt_text'] : 'Refresh Image';
        $refresh_title = (isset($options['refresh_title_text'])) ? $options['refresh_title_text'] : 'Refresh Image';
        $input_text = (isset($options['input_text'])) ? $options['input_text'] : 'Type the text:';
        $input_id = (isset($options['input_id'])) ? $options['input_id'] : 'captcha_code';
        $input_name = (isset($options['input_name'])) ? $options['input_name'] : $input_id;
        $input_attrs = (isset($options['input_attributes'])) ? $options['input_attributes'] : array();
        $image_attrs = (isset($options['image_attributes'])) ? $options['image_attributes'] : array();
        $error_html = (isset($options['error_html'])) ? $options['error_html'] : NULL;
        $namespace = (isset($options['namespace'])) ? $options['namespace'] : '';

        $rand = md5(uniqid($_SERVER['REMOTE_PORT'], TRUE));
        $securimage_path = rtrim($securimage_path, '/\\');
        $securimage_path = str_replace('\\', '/', $securimage_path);

        $image_attr = '';
        if (!is_array($image_attrs)) {
            $image_attrs = array();
        }
        if (!isset($image_attrs['style'])) {
            $image_attrs['style'] = 'float: left; padding-right: 5px';
        }
        $image_attrs['id'] = $image_id;

        $show_path = $securimage_path.'/securimage_show.php?';
        if ($show_image_url) {
            if (parse_url($show_image_url, PHP_URL_QUERY)) {
                $show_path = "{$show_image_url}&";
            } else {
                $show_path = "{$show_image_url}?";
            }
        }
        if (!empty($namespace)) {
            $show_path .= sprintf('namespace=%s&amp;', $namespace);
        }

        $image_attrs['src'] = $show_path.$rand;
        $image_attrs['alt'] = $image_alt;

        foreach ($image_attrs as $name => $val) {
            $image_attr .= sprintf('%s="%s" ', $name, htmlspecialchars($val));
        }

        $swf_path = $securimage_path.'/securimage_play.swf';
        $play_path = $securimage_path.'/securimage_play.php?';
        $icon_path = $securimage_path.'/images/audio_icon.png';
        $load_path = $securimage_path.'/images/loading.png';
        $js_path = $securimage_path.'/securimage.js';

        if (!empty($audio_icon_url)) {
            $icon_path = $audio_icon_url;
        }
        if (!empty($loading_icon_url)) {
            $load_path = $loading_icon_url;
        }

        if (!empty($audio_play_url)) {
            if (parse_url($audio_play_url, PHP_URL_QUERY)) {
                $play_path = "{$audio_play_url}&";
            } else {
                $play_path = "{$audio_play_url}?";
            }
        }

        if (!empty($namespace)) {
            $play_path .= sprintf('namespace=%s&amp;', $namespace);
        }
        if (!empty($audio_swf_url)) {
            $swf_path = $audio_swf_url;
        }

        $audio_obj = $image_id.'_audioObj';
        $html = '';

        if (($parts & Securimage::HTML_IMG) > 0) {
            $html .= sprintf('<img %s/>', $image_attr);
        }

        if (($parts & Securimage::HTML_AUDIO) > 0 && $show_audio_btn) {
            // html5 audio
            $html .= sprintf('<div id="%s_audio_div">', $image_id)."\n".
                sprintf('<audio id="%s_audio" preload="none" style="display: none">', $image_id)."\n";

            // check for existence and executability of LAME binary
            // prefer mp3 over wav by sourcing it first, if available
            if (is_executable(Securimage::$lame_binary_path)) {
                $html .= sprintf('<source id="%s_source_mp3" src="%sid=%s&amp;format=mp3" type="audio/mpeg">', $image_id, $play_path, uniqid())."\n";
            }

            // output wav source
            $html .= sprintf('<source id="%s_source_wav" src="%sid=%s" type="audio/wav">', $image_id, $play_path, uniqid())."\n";

            // flash audio button
            if (!$disable_flash_fbk) {
                $html .= sprintf('<object type="application/x-shockwave-flash" data="%s?bgcol=%s&amp;icon_file=%s&amp;audio_file=%s" height="%d" width="%d">',
                                 htmlspecialchars($swf_path),
                                 urlencode($audio_but_bg_col),
                                 urlencode($icon_path),
                                 urlencode(html_entity_decode($play_path)),
                                 $icon_size, $icon_size
                );

                $html .= sprintf('<param name="movie" value="%s?bgcol=%s&amp;icon_file=%s&amp;audio_file=%s" />',
                                 htmlspecialchars($swf_path),
                                 urlencode($audio_but_bg_col),
                                 urlencode($icon_path),
                                 urlencode(html_entity_decode($play_path))
                );

                $html .= '</object><br />';
            }

            // html5 audio close
            $html .= "</audio>\n</div>\n";

            // html5 audio controls
            $html .= sprintf('<div id="%s_audio_controls">', $image_id)."\n".
                sprintf('<a tabindex="-1" class="captcha_play_button" href="%sid=%s" onclick="return false">',
                        $play_path, uniqid()
                )."\n".
                sprintf('<img class="captcha_play_image" height="%d" width="%d" src="%s" alt="Play CAPTCHA Audio" style="border: 0px">', $icon_size,
                        $icon_size, htmlspecialchars($icon_path))."\n".
                sprintf('<img class="captcha_loading_image rotating" height="%d" width="%d" src="%s" alt="Loading audio" style="display: none">',
                        $icon_size, $icon_size, htmlspecialchars($load_path))."\n".
                "</a>\n<noscript>Enable Javascript for audio controls</noscript>\n".
                "</div>\n";

            // html5 javascript
            if (!$javascript_init) {
                $html .= sprintf('<script type="text/javascript" src="%s"></script>', $js_path)."\n";
                $javascript_init = TRUE;
            }
            $html .= '<script type="text/javascript">'.
                "$audio_obj = new SecurimageAudio({ audioElement: '{$image_id}_audio', controlsElement: '{$image_id}_audio_controls' });".
                "</script>\n";
        }

        if (($parts & Securimage::HTML_ICON_REFRESH) > 0 && $show_refresh_btn) {
            $icon_path = $securimage_path.'/images/refresh.png';
            if ($refresh_icon_url) {
                $icon_path = $refresh_icon_url;
            }
            $img_tag = sprintf('<img height="%d" width="%d" src="%s" alt="%s" onclick="this.blur()" style="border: 0px; vertical-align: bottom" />',
                               $icon_size, $icon_size, htmlspecialchars($icon_path), htmlspecialchars($refresh_alt));

            $html .= sprintf('<a tabindex="-1" style="border: 0" href="#" title="%s" onclick="%sdocument.getElementById(\'%s\').src = \'%s\' + Math.random(); this.blur(); return false">%s</a><br />',
                             htmlspecialchars($refresh_title),
                             ($audio_obj) ? "if (typeof window.{$audio_obj} !== 'undefined') {$audio_obj}.refresh(); " : '',
                             $image_id,
                             $show_path,
                             $img_tag
            );
        }

        if ($parts == Securimage::HTML_ALL) {
            $html .= '<div style="clear: both"></div>';
        }

        if (($parts & Securimage::HTML_INPUT_LABEL) > 0 && $show_input) {
            $html .= sprintf('<label for="%s">%s</label> ',
                             htmlspecialchars($input_id),
                             htmlspecialchars($input_text));

            if (!empty($error_html)) {
                $html .= $error_html;
            }
        }

        if (($parts & Securimage::HTML_INPUT) > 0 && $show_input) {
            $input_attr = '';
            if (!is_array($input_attrs)) {
                $input_attrs = array();
            }
            $input_attrs['type'] = 'text';
            $input_attrs['name'] = $input_name;
            $input_attrs['id'] = $input_id;

            foreach ($input_attrs as $name => $val) {
                $input_attr .= sprintf('%s="%s" ', $name, htmlspecialchars($val));
            }

            $html .= sprintf('<input %s/>', $input_attr);
        }

        return $html;
    }

    /**
     * Generates a new challenge and serves a captcha image.
     * Appropriate headers will be sent to the browser unless the *send_headers* option is false.
     * @param string $background_image The absolute or relative path to the background image to use as the background of the captcha image.
     *     <?php
     *     $img = new Securimage();
     *     $img->code_length = 6;
     *     $img->num_lines   = 5;
     *     $img->noise_level = 5;
     *     $img->show(); // sends the image and appropriate headers to browser
     *     exit;
     *     ?>
     */
    public function show($background_image = '') {
        set_error_handler(array(&$this, 'errorHandler'));

        if ($background_image != '' && is_readable($background_image)) {
            $this->bgimg = $background_image;
        }

        $this->doImage();
    }

    /**
     * The main image drawing routing, responsible for constructing the entire image and serving it
     */
    protected function doImage() {
        if (($this->use_transparent_text == TRUE || $this->bgimg != '') && function_exists('imagecreatetruecolor')) {
            $imagecreate = 'imagecreatetruecolor';
        } else {
            $imagecreate = 'imagecreate';
        }

        $this->im = $imagecreate($this->image_width, $this->image_height);
        $this->tmpimg = $imagecreate($this->image_width * $this->iscale, $this->image_height * $this->iscale);

        $this->allocateColors();
        imagepalettecopy($this->tmpimg, $this->im);

        $this->setBackground();

        $code = '';

        if ($this->getCaptchaId(FALSE) !== NULL) {
            // a captcha Id was supplied

            // check to see if a display_value for the captcha image was set
            if (is_string($this->display_value) && strlen($this->display_value) > 0) {
                $this->code_display = $this->display_value;
                $this->code = ($this->case_sensitive) ?
                    $this->display_value :
                    strtolower($this->display_value);
                $code = $this->code;
            } else {
                if ($this->openDatabase()) {
                    // no display_value, check the database for existing captchaId
                    $code = $this->getCodeFromDatabase();

                    // got back a result from the database with a valid code for captchaId
                    if (is_array($code)) {
                        $this->code = $code['code'];
                        $this->code_display = $code['code_disp'];
                        $code = $code['code'];
                    }
                }
            }
        }

        if ($code == '') {
            // if the code was not set using display_value or was not found in
            // the database, create a new code
            $this->createCode();
        }

        if ($this->noise_level > 0) {
            $this->drawNoise();
        }

        $this->drawWord();

        if ($this->perturbation > 0 && is_readable($this->ttf_file)) {
            $this->distortedCopy();
        }

        if ($this->num_lines > 0) {
            $this->drawLines();
        }

        if (trim($this->image_signature) != '') {
            $this->addSignature();
        }

        $this->output();
    }

    /**
     * Allocate the colors to be used for the image
     */
    protected function allocateColors() {
        // allocate bg color first for imagecreate
        $this->gdbgcolor = imagecolorallocate($this->im, $this->image_bg_color->r, $this->image_bg_color->g, $this->image_bg_color->b);

        $alpha = intval($this->text_transparency_percentage / 100 * 127);

        if ($this->use_transparent_text == TRUE) {
            $this->gdtextcolor = imagecolorallocatealpha($this->im, $this->text_color->r, $this->text_color->g, $this->text_color->b, $alpha);
            $this->gdlinecolor = imagecolorallocatealpha($this->im, $this->line_color->r, $this->line_color->g, $this->line_color->b, $alpha);
            $this->gdnoisecolor = imagecolorallocatealpha($this->im, $this->noise_color->r, $this->noise_color->g, $this->noise_color->b, $alpha);
        } else {
            $this->gdtextcolor = imagecolorallocate($this->im, $this->text_color->r, $this->text_color->g, $this->text_color->b);
            $this->gdlinecolor = imagecolorallocate($this->im, $this->line_color->r, $this->line_color->g, $this->line_color->b);
            $this->gdnoisecolor = imagecolorallocate($this->im, $this->noise_color->r, $this->noise_color->g, $this->noise_color->b);
        }

        $this->gdsignaturecolor = imagecolorallocate($this->im, $this->signature_color->r, $this->signature_color->g, $this->signature_color->b);
    }

    /**
     * The the background color, or background image to be used
     */
    protected function setBackground() {
        // set background color of image by drawing a rectangle since imagecreatetruecolor doesn't set a bg color
        imagefilledrectangle($this->im, 0, 0, $this->image_width, $this->image_height, $this->gdbgcolor);
        imagefilledrectangle($this->tmpimg, 0, 0, $this->image_width * $this->iscale, $this->image_height * $this->iscale, $this->gdbgcolor);

        if ($this->bgimg == '') {
            if ($this->background_directory != NULL && is_dir($this->background_directory) && is_readable($this->background_directory)) {
                $img = $this->getBackgroundFromDirectory();
                if ($img != FALSE) {
                    $this->bgimg = $img;
                }
            }
        }

        if ($this->bgimg == '') {
            return;
        }

        $dat = @getimagesize($this->bgimg);
        if ($dat == FALSE) {
            return;
        }

        switch ($dat[2]) {
            case 1:
                $newim = @imagecreatefromgif($this->bgimg);
                break;
            case 2:
                $newim = @imagecreatefromjpeg($this->bgimg);
                break;
            case 3:
                $newim = @imagecreatefrompng($this->bgimg);
                break;
            default:
                return;
        }

        if (!$newim) {
            return;
        }

        imagecopyresized($this->im, $newim, 0, 0, 0, 0, $this->image_width, $this->image_height, imagesx($newim), imagesy($newim));
    }

    /**
     * Scan the directory for a background image to use
     * @return string|bool
     */
    protected function getBackgroundFromDirectory() {
        $images = array();

        if (($dh = opendir($this->background_directory)) !== FALSE) {
            while (($file = readdir($dh)) !== FALSE) {
                if (preg_match('/(jpg|gif|png)$/i', $file)) {
                    $images[] = $file;
                }
            }

            closedir($dh);

            if (sizeof($images) > 0) {
                return rtrim($this->background_directory, '/').'/'.$images[mt_rand(0, sizeof($images) - 1)];
            }
        }

        return FALSE;
    }

    /**
     * Generate a new captcha ID or retrieve the current ID (if exists).
     * @param bool  $new If true, generates a new challenge and returns and ID.  If false, the existing captcha ID is returned, or null if none exists.
     * @param array $options Additional options to be passed to Securimage.
     *   $options must include database settings if they are not set directly in securimage.php
     * @return null|string Returns null if no captcha id set and new was false, or the captcha ID
     */
    public static function getCaptchaId($new = TRUE, array $options = array()) {
        if (is_null($new) || (bool)$new == TRUE) {
            $id = sha1(uniqid($_SERVER['REMOTE_ADDR'], TRUE));
            $opts = array(
                'no_session' => TRUE,
                'use_database' => TRUE
            );
            if (sizeof($options) > 0) {
                $opts = array_merge($options, $opts);
            }
            $si = new self($opts);
            Securimage::$_captchaId = $id;
            $si->createCode();

            return $id;
        } else {
            return Securimage::$_captchaId;
        }
    }

    /**
     * This method generates a new captcha code.
     * Generates a random captcha code based on *charset*, math problem, or captcha from the wordlist and saves the value to the session and/or database.
     */
    public function createCode() {
        $this->code = FALSE;

        switch ($this->captcha_type) {
            case self::SI_CAPTCHA_MATHEMATIC: {
                do {
                    $signs = array('+', '-', 'x');
                    $left = mt_rand(1, 10);
                    $right = mt_rand(1, 5);
                    $sign = $signs[mt_rand(0, 2)];

                    switch ($sign) {
                        case 'x':
                            $c = $left * $right;
                            break;
                        case '-':
                            $c = $left - $right;
                            break;
                        default:
                            $c = $left + $right;
                            break;
                    }
                } while ($c <= 0); // no negative #'s or 0

                $this->code = "$c";
                $this->code_display = "$left $sign $right";
                break;
            }

            case self::SI_CAPTCHA_WORDS:
                $words = $this->readCodeFromFile(2);
                $this->code = implode(' ', $words);
                $this->code_display = $this->code;
                break;

            default: {
                if ($this->use_wordlist && is_readable($this->wordlist_file)) {
                    $this->code = $this->readCodeFromFile();
                }

                if ($this->code == FALSE) {
                    $this->code = $this->generateCode($this->code_length);
                }

                $this->code_display = $this->code;
                $this->code = ($this->case_sensitive) ? $this->code : strtolower($this->code);
            } // default
        }

        $this->saveData();
    }

    /**
     * Gets a captcha code from a file containing a list of words.
     * Seek to a random offset in the file and reads a block of data and returns a line from the file.
     * @param int $numWords Number of words (lines) to read from the file
     * @return string|array|bool  Returns a string if only one word is to be read, or an array of words
     */
    protected function readCodeFromFile($numWords = 1) {
        $strpos_func = 'strpos';
        $strlen_func = 'strlen';
        $substr_func = 'substr';
        $strtolower_func = 'strtolower';
        $mb_support = FALSE;

        if (!empty($this->wordlist_file_encoding)) {
            if (!extension_loaded('mbstring')) {
                trigger_error("wordlist_file_encoding option set, but PHP does not have mbstring support", E_USER_WARNING);

                return FALSE;
            }

            // emits PHP warning if not supported
            $mb_support = mb_internal_encoding($this->wordlist_file_encoding);

            if (!$mb_support) {
                return FALSE;
            }

            $strpos_func = 'mb_strpos';
            $strlen_func = 'mb_strlen';
            $substr_func = 'mb_substr';
            $strtolower_func = 'mb_strtolower';
        }

        $fp = fopen($this->wordlist_file, 'rb');
        if (!$fp) {
            return FALSE;
        }

        $fsize = filesize($this->wordlist_file);
        if ($fsize < 128) {
            return FALSE; // too small of a list to be effective
        }

        if ((int)$numWords < 1 || (int)$numWords > 5) {
            $numWords = 1;
        }

        $words = array();
        $i = 0;
        do {
            fseek($fp, mt_rand(0, $fsize - 128), SEEK_SET); // seek to a random position of file from 0 to filesize-128
            $data = fread($fp, 128); // read a chunk from our random position

            if ($mb_support !== FALSE) {
                $data = mb_ereg_replace("\r?\n", "\n", $data);
            } else {
                $data = preg_replace("/\r?\n/", "\n", $data);
            }

            $start = @$strpos_func($data, "\n", mt_rand(0, 56)) + 1; // random start position
            $end = @$strpos_func($data, "\n", $start);          // find end of word

            if ($start === FALSE) {
                // picked start position at end of file
                continue;
            } else {
                if ($end === FALSE) {
                    $end = $strlen_func($data);
                }
            }

            $word = $strtolower_func($substr_func($data, $start, $end - $start)); // return a line of the file

            if ($mb_support) {
                // convert to UTF-8 for imagettftext
                $word = mb_convert_encoding($word, 'UTF-8', $this->wordlist_file_encoding);
            }

            $words[] = $word;
        } while (++$i < $numWords);

        fclose($fp);

        if ($numWords < 2) {
            return $words[0];
        } else {
            return $words;
        }
    }

    /**
     * Generates a random captcha code from the set character set
     * @see Securimage::$charset  Charset option
     * @return string A randomly generated CAPTCHA code
     */
    protected function generateCode() {
        $code = '';

        if (function_exists('mb_strlen')) {
            for ($i = 1, $cslen = mb_strlen($this->charset, 'UTF-8'); $i <= $this->code_length; ++$i) {
                $code .= mb_substr($this->charset, mt_rand(0, $cslen - 1), 1, 'UTF-8');
            }
        } else {
            for ($i = 1, $cslen = strlen($this->charset); $i <= $this->code_length; ++$i) {
                $code .= substr($this->charset, mt_rand(0, $cslen - 1), 1);
            }
        }

        return $code;
    }

    /**
     * Save CAPTCHA data to session and database (if configured)
     */
    protected function saveData() {
        if ($this->no_session != TRUE) {
            if (isset($_SESSION['securimage_code_value']) && is_scalar($_SESSION['securimage_code_value'])) {
                // fix for migration from v2 - v3
                unset($_SESSION['securimage_code_value']);
                unset($_SESSION['securimage_code_ctime']);
            }

            $_SESSION['securimage_code_disp'] [$this->namespace] = $this->code_display;
            $_SESSION['securimage_code_value'][$this->namespace] = $this->code;
            $_SESSION['securimage_code_ctime'][$this->namespace] = time();
            $_SESSION['securimage_code_audio'][$this->namespace] = NULL; // clear previous audio, if set
        }

        if ($this->use_database) {
            $this->saveCodeToDatabase();
        }
    }

    /**
     * Saves the CAPTCHA data to the configured database.
     */
    protected function saveCodeToDatabase() {
        $success = FALSE;
        $this->openDatabase();

        if ($this->use_database && $this->pdo_conn) {
            $id = $this->getCaptchaId(FALSE);
            $ip = $_SERVER['REMOTE_ADDR'];

            if (empty($id)) {
                $id = $ip;
            }

            $time = time();
            $code = $this->code;
            $code_disp = $this->code_display;

            // This is somewhat expensive in PDO Sqlite3 (when there is something to delete)
            // Clears previous captcha for this client from database so we can do a straight insert
            // without having to do INSERT ... ON DUPLICATE KEY or a find/update
            $this->clearCodeFromDatabase();

            $query = "INSERT INTO {$this->database_table} ("
                ."id, code, code_display, namespace, created) "
                ."VALUES(?, ?, ?, ?, ?)";

            $stmt = $this->pdo_conn->prepare($query);
            $success = $stmt->execute(array($id, $code, $code_disp, $this->namespace, $time));

            if (!$success) {
                $err = $stmt->errorInfo();
                $error = "Failed to insert code into database. {$err[1]}: {$err[2]}.";

                if ($this->database_driver == self::SI_DRIVER_SQLITE3) {
                    $err14 = ($err[1] == 14);
                    if ($err14) {
                        $error .= sprintf(" Ensure database directory and file are writeable by user '%s' (%d).",
                                          get_current_user(), getmyuid());
                    }
                }

                trigger_error($error, E_USER_WARNING);
            }
        }

        return $success !== FALSE;
    }

    /**
     * Draws random noise on the image
     */
    protected function drawNoise() {
        if ($this->noise_level > 10) {
            $noise_level = 10;
        } else {
            $noise_level = $this->noise_level;
        }

        $t0 = microtime(TRUE);

        $noise_level *= 125; // an arbitrary number that works well on a 1-10 scale

        $points = $this->image_width * $this->image_height * $this->iscale;
        $height = $this->image_height * $this->iscale;
        $width = $this->image_width * $this->iscale;
        for ($i = 0; $i < $noise_level; ++$i) {
            $x = mt_rand(10, $width);
            $y = mt_rand(10, $height);
            $size = mt_rand(7, 10);
            if ($x - $size <= 0 && $y - $size <= 0) {
                continue; // dont cover 0,0 since it is used by imagedistortedcopy
            }

            imagefilledarc($this->tmpimg, $x, $y, $size, $size, 0, 360, $this->gdnoisecolor, IMG_ARC_PIE);
        }

        $t1 = microtime(TRUE);

        $t = $t1 - $t0;

        /*
        // DEBUG
        imagestring($this->tmpimg, 5, 25, 30, "$t", $this->gdnoisecolor);
        header('content-type: image/png');
        imagepng($this->tmpimg);
        exit;
        */
    }

    /**
     * Draws the captcha code on the image
     */
    protected function drawWord() {
        $width2 = $this->image_width * $this->iscale;
        $height2 = $this->image_height * $this->iscale;
        $ratio = ($this->font_ratio) ? $this->font_ratio : 0.4;

        if ((float)$ratio < 0.1 || (float)$ratio >= 1) {
            $ratio = 0.4;
        }

        if (!is_readable($this->ttf_file)) {
            imagestring($this->im, 4, 10, ($this->image_height / 2) - 5, 'Failed to load TTF font file!', $this->gdtextcolor);
        } else {
            if ($this->perturbation > 0) {
                $font_size = $height2 * $ratio;
                $bb = imageftbbox($font_size, 0, $this->ttf_file, $this->code_display);
                $tx = $bb[4] - $bb[0];
                $ty = $bb[5] - $bb[1];
                $x = floor($width2 / 2 - $tx / 2 - $bb[0]);
                $y = round($height2 / 2 - $ty / 2 - $bb[1]);

                imagettftext($this->tmpimg, $font_size, 0, (int)$x, (int)$y, $this->gdtextcolor, $this->ttf_file, $this->code_display);
            } else {
                $font_size = $this->image_height * $ratio;
                $bb = imageftbbox($font_size, 0, $this->ttf_file, $this->code_display);
                $tx = $bb[4] - $bb[0];
                $ty = $bb[5] - $bb[1];
                $x = floor($this->image_width / 2 - $tx / 2 - $bb[0]);
                $y = round($this->image_height / 2 - $ty / 2 - $bb[1]);

                imagettftext($this->im, $font_size, 0, (int)$x, (int)$y, $this->gdtextcolor, $this->ttf_file, $this->code_display);
            }
        }

        // DEBUG
        //$this->im = $this->tmpimg;
        //$this->output();
    }

    /**
     * Copies the captcha image to the final image with distortion applied
     */
    protected function distortedCopy() {
        $numpoles = 3; // distortion factor
        // make array of poles AKA attractor points
        for ($i = 0; $i < $numpoles; ++$i) {
            $px[$i] = mt_rand($this->image_width * 0.2, $this->image_width * 0.8);
            $py[$i] = mt_rand($this->image_height * 0.2, $this->image_height * 0.8);
            $rad[$i] = mt_rand($this->image_height * 0.2, $this->image_height * 0.8);
            $tmp = ((-$this->frand()) * 0.15) - .15;
            $amp[$i] = $this->perturbation * $tmp;
        }

        $bgCol = imagecolorat($this->tmpimg, 0, 0);
        $width2 = $this->iscale * $this->image_width;
        $height2 = $this->iscale * $this->image_height;
        imagepalettecopy($this->im, $this->tmpimg); // copy palette to final image so text colors come across
        // loop over $img pixels, take pixels from $tmpimg with distortion field
        for ($ix = 0; $ix < $this->image_width; ++$ix) {
            for ($iy = 0; $iy < $this->image_height; ++$iy) {
                $x = $ix;
                $y = $iy;
                for ($i = 0; $i < $numpoles; ++$i) {
                    $dx = $ix - $px[$i];
                    $dy = $iy - $py[$i];
                    if ($dx == 0 && $dy == 0) {
                        continue;
                    }
                    $r = sqrt($dx * $dx + $dy * $dy);
                    if ($r > $rad[$i]) {
                        continue;
                    }
                    $rscale = $amp[$i] * sin(3.14 * $r / $rad[$i]);
                    $x += $dx * $rscale;
                    $y += $dy * $rscale;
                }
                $c = $bgCol;
                $x *= $this->iscale;
                $y *= $this->iscale;
                if ($x >= 0 && $x < $width2 && $y >= 0 && $y < $height2) {
                    $c = imagecolorat($this->tmpimg, $x, $y);
                }
                if ($c != $bgCol) { // only copy pixels of letters to preserve any background image
                    imagesetpixel($this->im, $ix, $iy, $c);
                }
            }
        }
    }

    /**
     * Return a random float between 0 and 0.9999
     * @return float Random float between 0 and 0.9999
     */
    function frand() {
        return 0.0001 * mt_rand(0, 9999);
    }

    /**
     * Draws distorted lines on the image
     */
    protected function drawLines() {
        for ($line = 0; $line < $this->num_lines; ++$line) {
            $x = $this->image_width * (1 + $line) / ($this->num_lines + 1);
            $x += (0.5 - $this->frand()) * $this->image_width / $this->num_lines;
            $y = mt_rand($this->image_height * 0.1, $this->image_height * 0.9);

            $theta = ($this->frand() - 0.5) * M_PI * 0.7;
            $w = $this->image_width;
            $len = mt_rand($w * 0.4, $w * 0.7);
            $lwid = mt_rand(0, 2);

            $k = $this->frand() * 0.6 + 0.2;
            $k = $k * $k * 0.5;
            $phi = $this->frand() * 6.28;
            $step = 0.5;
            $dx = $step * cos($theta);
            $dy = $step * sin($theta);
            $n = $len / $step;
            $amp = 1.5 * $this->frand() / ($k + 5.0 / $len);
            $x0 = $x - 0.5 * $len * cos($theta);
            $y0 = $y - 0.5 * $len * sin($theta);

            $ldx = round(-$dy * $lwid);
            $ldy = round($dx * $lwid);

            for ($i = 0; $i < $n; ++$i) {
                $x = $x0 + $i * $dx + $amp * $dy * sin($k * $i * $step + $phi);
                $y = $y0 + $i * $dy - $amp * $dx * sin($k * $i * $step + $phi);
                imagefilledrectangle($this->im, $x, $y, $x + $lwid, $y + $lwid, $this->gdlinecolor);
            }
        }
    }

    /**
     * Print signature text on image
     */
    protected function addSignature() {
        $bbox = imagettfbbox(10, 0, $this->signature_font, $this->image_signature);
        $textlen = $bbox[2] - $bbox[0];
        $x = $this->image_width - $textlen - 5;
        $y = $this->image_height - 3;

        imagettftext($this->im, 10, 0, $x, $y, $this->gdsignaturecolor, $this->signature_font, $this->image_signature);
    }

    /**
     * Sends the appropriate image and cache headers and outputs image to the browser
     */
    protected function output() {
        if ($this->canSendHeaders() || $this->send_headers == FALSE) {
            if ($this->send_headers) {
                // only send the content-type headers if no headers have been output
                // this will ease debugging on misconfigured servers where warnings
                // may have been output which break the image and prevent easily viewing
                // source to see the error.
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: post-check=0, pre-check=0", FALSE);
                header("Pragma: no-cache");
            }

            switch ($this->image_type) {
                case self::SI_IMAGE_JPEG:
                    if ($this->send_headers) {
                        header("Content-Type: image/jpeg");
                    }
                    imagejpeg($this->im, NULL, 90);
                    break;
                case self::SI_IMAGE_GIF:
                    if ($this->send_headers) {
                        header("Content-Type: image/gif");
                    }
                    imagegif($this->im);
                    break;
                default:
                    if ($this->send_headers) {
                        header("Content-Type: image/png");
                    }
                    imagepng($this->im);
                    break;
            }
        } else {
            echo '<hr /><strong>Failed to generate captcha image, content has already been output.<br />This is most likely due to misconfiguration or a PHP error was sent to the browser.</strong>';
        }

        imagedestroy($this->im);
        restore_error_handler();

        if (!$this->no_exit) {
            exit;
        }
    }

    /**
     * Checks to see if headers can be sent and if any error has been output
     * to the browser
     * @return bool true if it is safe to send headers, false if not
     */
    protected function canSendHeaders() {
        if (headers_sent()) {
            // output has been flushed and headers have already been sent
            return FALSE;
        } else {
            if (strlen((string)ob_get_contents()) > 0) {
                // headers haven't been sent, but there is data in the buffer that will break image and audio data
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Get the time in seconds that it took to solve the captcha.
     * @return int The time in seconds from when the code was created, to when it was solved
     */
    public function getTimeToSolve() {
        return $this->_timeToSolve;
    }

    /**
     * Set the namespace for the captcha being stored in the session or database.
     * Namespaces are useful when multiple captchas need to be displayed on a single page.
     * @param string $namespace Namespace value, String consisting of characters "a-zA-Z0-9_-"
     */
    public function setNamespace($namespace) {
        $namespace = preg_replace('/[^a-z0-9-_]/i', '', $namespace);
        $namespace = substr($namespace, 0, 64);

        if (!empty($namespace)) {
            $this->namespace = $namespace;
        } else {
            $this->namespace = 'default';
        }
    }

    /**
     * Generate an audible captcha in WAV format and send it to the browser with appropriate headers.
     * Example:
     *     <?php
     *     $img = new Securimage();
     *     $img->outputAudioFile(); // outputs a wav file to the browser
     *     exit;
     *     ?>
     * @param string $format
     */
    public function outputAudioFile($format = NULL) {
        set_error_handler(array(&$this, 'errorHandler'));

        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = TRUE;
            $rangeId = (isset($_SERVER['HTTP_X_PLAYBACK_SESSION_ID'])) ?
                'ID'.$_SERVER['HTTP_X_PLAYBACK_SESSION_ID'] :
                'ID'.md5($_SERVER['REQUEST_URI']);
            $uniq = $rangeId;
        } else {
            $uniq = md5(uniqid(microtime()));
        }

        try {
            if (!($audio = $this->getAudioData())) {
                // if previously generated audio not found for current captcha
                require_once dirname(__FILE__).'/WavFile.php';
                $audio = $this->getAudibleCode();

                if (strtolower($format) == 'mp3') {
                    $audio = $this->wavToMp3($audio);
                }

                $this->saveAudioData($audio);
            }
        } catch (Exception $ex) {
            if (($fp = @fopen(dirname(__FILE__).'/si.error_log', 'a+')) !== FALSE) {
                fwrite($fp, date('Y-m-d H:i:s').': Securimage audio error "'.$ex->getMessage().'"'."\n");
                fclose($fp);
            }

            $audio = $this->audioError();
        }

        if ($this->no_session != TRUE) {
            // close session to make it available to other requests in the event
            // streaming the audio takes sevaral seconds or more
            session_write_close();
        }

        if ($this->canSendHeaders() || $this->send_headers == FALSE) {
            if ($this->send_headers) {
                if ($format == 'mp3') {
                    $ext = 'mp3';
                    $type = 'audio/mpeg';
                } else {
                    $ext = 'wav';
                    $type = 'audio/wav';
                }

                header('Accept-Ranges: bytes');
                header("Content-Disposition: attachment; filename=\"securimage_audio-{$uniq}.{$ext}\"");
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Expires: Sun, 1 Jan 2000 12:00:00 GMT');
                header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
                header('Content-type: '.$type);
            }

            $this->rangeDownload($audio);
        } else {
            echo '<hr /><strong>'
                .'Failed to generate audio file, content has already been '
                .'output.<br />This is most likely due to misconfiguration or '
                .'a PHP error was sent to the browser.</strong>';
        }

        restore_error_handler();

        if (!$this->no_exit) {
            exit;
        }
    }

    /**
     * Gets audio file contents from the session or database
     * @return string|boolean Audio contents on success, or false if no audio found in session or DB
     */
    protected function getAudioData() {
        if ($this->no_session != TRUE) {
            if (isset($_SESSION['securimage_code_audio'][$this->namespace])) {
                return $_SESSION['securimage_code_audio'][$this->namespace];
            }
        }

        if ($this->use_database) {
            $this->openDatabase();
            $code = $this->getCodeFromDatabase();

            if (!empty($code['audio_data'])) {
                return $code['audio_data'];
            }
        }

        return FALSE;
    }

    /**
     * Generates an audio captcha in WAV format
     * @return string The audio representation of the captcha in Wav format
     */
    protected function getAudibleCode() {
        $letters = array();
        $code = $this->getCode(TRUE, TRUE);

        if (empty($code) || $code['code'] == '') {
            if (strlen($this->display_value) > 0) {
                $code = array('code' => $this->display_value, 'display' => $this->display_value);
            } else {
                $this->createCode();
                $code = $this->getCode(TRUE);
            }
        }

        if (empty($code)) {
            $error = 'Failed to get audible code (are database settings correct?).  Check the error log for details';
            trigger_error($error, E_USER_WARNING);
            throw new Exception($error);
        }

        if (preg_match('/(\d+) (\+|-|x) (\d+)/i', $code['display'], $eq)) {
            $math = TRUE;

            $left = $eq[1];
            $sign = str_replace(array('+', '-', 'x'), array('plus', 'minus', 'times'), $eq[2]);
            $right = $eq[3];

            $letters = array($left, $sign, $right);
        } else {
            $math = FALSE;

            $length = strlen($code['display']);

            for ($i = 0; $i < $length; ++$i) {
                $letter = $code['display']{$i};
                $letters[] = $letter;
            }
        }

        try {
            return $this->generateWAV($letters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Generate a wav file given the $letters in the code
     * @param array $letters The letters making up the captcha
     * @return string The audio content in WAV format
     */
    protected function generateWAV($letters) {
        $wavCaptcha = new WavFile();
        $first = TRUE;     // reading first wav file

        if ($this->audio_use_sox && !is_executable($this->sox_binary_path)) {
            throw new Exception("Path to SoX binary is incorrect or not executable");
        }

        foreach ($letters as $letter) {
            $letter = strtoupper($letter);

            try {
                $letter_file = realpath($this->audio_path).DIRECTORY_SEPARATOR.$letter.'.wav';

                if ($this->audio_use_sox) {
                    $sox_cmd = sprintf("%s %s -t wav - %s", $this->sox_binary_path, $letter_file, $this->getSoxEffectChain());

                    $data = `$sox_cmd`;

                    $l = new WavFile();
                    $l->setIgnoreChunkSizes(TRUE);
                    $l->setWavData($data);
                } else {
                    $l = new WavFile($letter_file);
                }

                if ($first) {
                    // set sample rate, bits/sample, and # of channels for file based on first letter
                    $wavCaptcha->setSampleRate($l->getSampleRate())->setBitsPerSample($l->getBitsPerSample())->setNumChannels($l->getNumChannels());
                    $first = FALSE;
                }

                // append letter to the captcha audio
                $wavCaptcha->appendWav($l);

                // random length of silence between $audio_gap_min and $audio_gap_max
                if ($this->audio_gap_max > 0 && $this->audio_gap_max > $this->audio_gap_min) {
                    $wavCaptcha->insertSilence(mt_rand($this->audio_gap_min, $this->audio_gap_max) / 1000.0);
                }
            } catch (Exception $ex) {
                // failed to open file, or the wav file is broken or not supported
                // 2 wav files were not compatible, different # channels, bits/sample, or sample rate
                throw new Exception("Error generating audio captcha on letter '$letter': ".$ex->getMessage());
            }
        }

        /********* Set up audio filters *****************************/
        $filters = array();

        if ($this->audio_use_noise == TRUE) {
            // use background audio - find random file
            $wavNoise = FALSE;
            $randOffset = 0;

            /*
            // uncomment to try experimental SoX noise generation.
            // warning: sounds may be considered annoying
            if ($this->audio_use_sox) {
                $duration = $wavCaptcha->getDataSize() / ($wavCaptcha->getBitsPerSample() / 8) /
                            $wavCaptcha->getNumChannels() / $wavCaptcha->getSampleRate();
                $duration = round($duration, 2);
                $wavNoise = new WavFile();
                $wavNoise->setIgnoreChunkSizes(true);
                $noiseData = $this->getSoxNoiseData($duration,
                                                    $wavCaptcha->getNumChannels(),
                                                    $wavCaptcha->getSampleRate(),
                                                    $wavCaptcha->getBitsPerSample());
                $wavNoise->setWavData($noiseData, true);

            } else
            */
            if (($noiseFile = $this->getRandomNoiseFile()) !== FALSE) {
                try {
                    $wavNoise = new WavFile($noiseFile, FALSE);
                } catch (Exception $ex) {
                    throw $ex;
                }

                // start at a random offset from the beginning of the wavfile
                // in order to add more randomness

                $randOffset = 0;

                if ($wavNoise->getNumBlocks() > 2 * $wavCaptcha->getNumBlocks()) {
                    $randBlock = mt_rand(0, $wavNoise->getNumBlocks() - $wavCaptcha->getNumBlocks());
                    $wavNoise->readWavData($randBlock * $wavNoise->getBlockAlign(), $wavCaptcha->getNumBlocks() * $wavNoise->getBlockAlign());
                } else {
                    $wavNoise->readWavData();
                    $randOffset = mt_rand(0, $wavNoise->getNumBlocks() - 1);
                }
            }

            if ($wavNoise !== FALSE) {
                $mixOpts = array(
                    'wav' => $wavNoise,
                    'loop' => TRUE,
                    'blockOffset' => $randOffset
                );

                $filters[WavFile::FILTER_MIX] = $mixOpts;
                $filters[WavFile::FILTER_NORMALIZE] = $this->audio_mix_normalization;
            }
        }

        if ($this->degrade_audio == TRUE) {
            // add random noise.
            // any noise level below 95% is intensely distorted and not pleasant to the ear
            $filters[WavFile::FILTER_DEGRADE] = mt_rand(95, 98) / 100.0;
        }

        if (!empty($filters)) {
            $wavCaptcha->filter($filters);  // apply filters to captcha audio
        }

        return $wavCaptcha->__toString();
    }

    /**
     * Get a random effect or chain of effects to apply to a segment of the
     * audio file.
     * These effects should increase the randomness of the audio for
     * a particular letter/number by modulating the signal.  The SoX effects
     * used are *bend*, *chorus*, *overdrive*, *pitch*, *reverb*, *tempo*, and
     * *tremolo*.
     * For each effect selected, random parameters are supplied to the effect.
     * @param int $numEffects How many effects to chain together
     * @return string  A string of valid SoX effects and their respective options.
     */
    protected function getSoxEffectChain($numEffects = 2) {
        $effectsList = array('bend', 'chorus', 'overdrive', 'pitch', 'reverb', 'tempo', 'tremolo');
        $effects = array_rand($effectsList, $numEffects);
        $outEffects = array();

        if (!is_array($effects)) {
            $effects = array($effects);
        }

        foreach ($effects as $effect) {
            $effect = $effectsList[$effect];

            switch ($effect) {
                case 'bend':
                    $delay = mt_rand(0, 15) / 100.0;
                    $cents = mt_rand(-120, 120);
                    $dur = mt_rand(75, 400) / 100.0;
                    $outEffects[] = "$effect $delay,$cents,$dur";
                    break;
                case 'chorus':
                    $gainIn = mt_rand(75, 90) / 100.0;
                    $gainOut = mt_rand(70, 95) / 100.0;
                    $chorStr = "$effect $gainIn $gainOut";

                    for ($i = 0; $i < mt_rand(2, 3); ++$i) {
                        $delay = mt_rand(20, 100);
                        $decay = mt_rand(10, 100) / 100.0;
                        $speed = mt_rand(20, 50) / 100.0;
                        $depth = mt_rand(150, 250) / 100.0;

                        $chorStr .= " $delay $decay $speed $depth -s";
                    }

                    $outEffects[] = $chorStr;
                    break;
                case 'overdrive':
                    $gain = mt_rand(5, 25);
                    $color = mt_rand(20, 70);
                    $outEffects[] = "$effect $gain $color";
                    break;
                case 'pitch':
                    $cents = mt_rand(-300, 300);
                    $outEffects[] = "$effect $cents";
                    break;
                case 'reverb':
                    $reverberance = mt_rand(20, 80);
                    $damping = mt_rand(10, 80);
                    $scale = mt_rand(85, 100);
                    $depth = mt_rand(90, 100);
                    $predelay = mt_rand(0, 5);
                    $outEffects[] = "$effect $reverberance $damping $scale $depth $predelay";
                    break;
                case 'tempo':
                    $factor = mt_rand(65, 135) / 100.0;
                    $outEffects[] = "$effect -s $factor";
                    break;
                case 'tremolo':
                    $hz = mt_rand(10, 30);
                    $depth = mt_rand(40, 85);
                    $outEffects[] = "$effect $hz $depth";
                    break;
            }
        }

        return implode(' ', $outEffects);
    }

    /**
     * Gets and returns the path to a random noise file from the audio noise directory.
     * @return bool|string  false if a file could not be found, or a string containing the path to the file.
     */
    public function getRandomNoiseFile() {
        $return = FALSE;

        if (($dh = opendir($this->audio_noise_path)) !== FALSE) {
            $list = array();

            while (($file = readdir($dh)) !== FALSE) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (strtolower(substr($file, -4)) != '.wav') {
                    continue;
                }

                $list[] = $file;
            }

            closedir($dh);

            if (sizeof($list) > 0) {
                $file = $list[array_rand($list, 1)];
                $return = $this->audio_noise_path.DIRECTORY_SEPARATOR.$file;

                if (!is_readable($return)) {
                    $return = FALSE;
                }
            }
        }

        return $return;
    }

    /**
     * Convert WAV data to MP3 using the Lame MP3 encoder binary
     * @param string $data Contents of the WAV file to convert
     * @return string       MP3 file data
     */
    protected function wavToMp3($data) {
        if (!file_exists(self::$lame_binary_path) || !is_executable(self::$lame_binary_path)) {
            throw new Exception('Lame binary "'.$this->lame_binary_path.'" does not exist or is not executable');
        }

        // size of wav data input
        $size = strlen($data);

        // file descriptors for reading and writing to the Lame process
        $descriptors = array(
            0 => array('pipe', 'r'), // stdin
            1 => array('pipe', 'w'), // stdout
            2 => array('pipe', 'a'), // stderr
        );

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // workaround for Windows conversion
            // writing to STDIN seems to hang indefinitely after writing approximately 0xC400 bytes
            $wavinput = tempnam(sys_get_temp_dir(), 'wav');
            if (!$wavinput) {
                throw new Exception('Failed to create temporary file for WAV to MP3 conversion');
            }
            file_put_contents($wavinput, $data);
            $size = 0;
        } else {
            $wavinput = '-'; // stdin
        }

        // Mono, variable bit rate, 32 kHz sampling rate, read WAV from stdin, write MP3 to stdout
        $cmd = sprintf("%s -m m -v -b 32 %s -", self::$lame_binary_path, $wavinput);
        $proc = proc_open($cmd, $descriptors, $pipes);

        if (!is_resource($proc)) {
            throw new Exception('Failed to open process for MP3 encoding');
        }

        stream_set_blocking($pipes[0], 0); // set stdin to be non-blocking

        for ($written = 0; $written < $size; $written += $len) {
            // write to stdin until all WAV data is written
            $len = fwrite($pipes[0], substr($data, $written, 0x20000));

            if ($len === 0) {
                // fwrite wrote no data, make sure process is still alive, otherwise wait for it to process
                $status = proc_get_status($proc);
                if ($status['running'] === FALSE) {
                    break;
                }
                usleep(25000);
            } else {
                if ($written < $size) {
                    // couldn't write all data, small pause and try again
                    usleep(10000);
                } else {
                    if ($len === FALSE) {
                        // fwrite failed, should not happen
                        break;
                    }
                }
            }
        }

        fclose($pipes[0]);

        $data = stream_get_contents($pipes[1]);
        $err = trim(stream_get_contents($pipes[2]));

        fclose($pipes[1]);
        fclose($pipes[2]);

        $return = proc_close($proc);

        if ($wavinput != '-') {
            unlink($wavinput);
        } // delete temp file on Windows

        if ($return !== 0) {
            throw new Exception("Failed to convert WAV to MP3.  Shell returned ({$return}): {$err}");
        } else {
            if ($written < $size) {
                throw new Exception('Failed to convert WAV to MP3.  Failed to write all data to encoder');
            }
        }

        return $data;
    }

    /**
     * Save audio data to session and/or the configured database
     * @param string $data The CAPTCHA audio data
     */
    protected function saveAudioData($data) {
        if ($this->no_session != TRUE) {
            $_SESSION['securimage_code_audio'][$this->namespace] = $data;
        }

        if ($this->use_database) {
            $this->saveAudioToDatabase($data);
        }
    }

    /**
     * Saves CAPTCHA audio to the configured database
     * @param string $data Audio data
     * @return boolean true on success, false on failure
     */
    protected function saveAudioToDatabase($data) {
        $success = FALSE;
        $this->openDatabase();

        if ($this->use_database && $this->pdo_conn) {
            $id = $this->getCaptchaId(FALSE);
            $ip = $_SERVER['REMOTE_ADDR'];
            $ns = $this->namespace;

            if (empty($id)) {
                $id = $ip;
            }

            $query = "UPDATE {$this->database_table} SET audio_data = :audioData WHERE id = :id AND namespace = :namespace";
            $stmt = $this->pdo_conn->prepare($query);
            $stmt->bindParam(':audioData', $data, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':namespace', $ns);
            $success = $stmt->execute();
        }

        return $success !== FALSE;
    }

    /**
     * Return a wav file saying there was an error generating file
     * @return string The binary audio contents
     */
    protected function audioError() {
        return @file_get_contents(dirname(__FILE__).'/audio/en/error.wav');
    }

    /**
     * Output audio data with http range support.  Typically this shouldn't be
     * called directly unless being used with a custom implentation.  Use
     * Securimage::outputAudioFile instead.
     * @param string $audio Raw wav or mp3 audio file content
     */
    public function rangeDownload($audio) {
        /* Congratulations Firefox Android/Linux/Windows for being the most
         * sensible browser of all when streaming HTML5 audio!
         * Chrome on Android and iOS on iPad/iPhone both make extra HTTP requests
         * for the audio whether on WiFi or the mobile network resulting in
         * multiple downloads of the audio file and wasted bandwidth.
         * If I'm doing something wrong in this code or anyone knows why, I'd
         * love to hear from you.
         */
        $audioLength = $size = strlen($audio);

        if (isset($_SERVER['HTTP_RANGE'])) {
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE']); // bytes=byte-range-set
            $range = trim($range);

            if (strpos($range, ',') !== FALSE) {
                // eventually, we should handle requests with multiple ranges
                // most likely these types of requests will never be sent
                header('HTTP/1.1 416 Range Not Satisfiable');
                echo "<h1>Range Not Satisfiable</h1>";
                exit;
            } else {
                if (preg_match('/(\d+)-(\d+)/', $range, $match)) {
                    // bytes n - m
                    $range = array(intval($match[1]), intval($match[2]));
                } else {
                    if (preg_match('/(\d+)-$/', $range, $match)) {
                        // bytes n - last byte of file
                        $range = array(intval($match[1]), NULL);
                    } else {
                        if (preg_match('/-(\d+)/', $range, $match)) {
                            // final n bytes of file
                            $range = array($size - intval($match[1]), $size - 1);
                        }
                    }
                }
            }

            if ($range[1] === NULL) {
                $range[1] = $size - 1;
            }
            $length = $range[1] - $range[0] + 1;
            $audio = substr($audio, $range[0], $length);
            $audioLength = strlen($audio);

            header('HTTP/1.1 206 Partial Content');
            header("Content-Range: bytes {$range[0]}-{$range[1]}/{$size}");

            if ($range[0] < 0 || $range[1] >= $size || $range[0] >= $size || $range[0] > $range[1]) {
                header('HTTP/1.1 416 Range Not Satisfiable');
                echo "<h1>Range Not Satisfiable</h1>";
                exit;
            }
        }

        header('Content-Length: '.$audioLength);

        echo $audio;
    }

    /**
     * The error handling function used when outputting captcha image or audio.
     * This error handler helps determine if any errors raised would
     * prevent captcha image or audio from displaying.  If they have
     * no effect on the output buffer or headers, true is returned so
     * the script can continue processing.
     * See https://github.com/dapphp/securimage/issues/15
     * @param int    $errno PHP error number
     * @param string $errstr String description of the error
     * @param string $errfile File error occurred in
     * @param int    $errline Line the error occurred on in file
     * @param array  $errcontext Additional context information
     * @return boolean true if the error was handled, false if PHP should handle the error
     */
    public function errorHandler($errno, $errstr, $errfile = '', $errline = 0, $errcontext = array()) {
        // get the current error reporting level
        $level = error_reporting();

        // if error was supressed or $errno not set in current error level
        if ($level == 0 || ($level & $errno) == 0) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * This function is not yet used.
     * Generate random background noise from sweeping oscillators
     * @param float $duration How long in seconds the generated sound will be
     * @param int   $numChannels Number of channels in output wav
     * @param int   $sampleRate Sample rate of output wav
     * @param int   $bitRate Bits per sample (8, 16, 24)
     * @return string          Audio data in wav format
     */
    protected function getSoxNoiseData($duration, $numChannels, $sampleRate, $bitRate) {
        $shapes = array('sine', 'square', 'triangle', 'sawtooth', 'trapezium');
        $steps = array(':', '+', '/', '-');
        $selShapes = array_rand($shapes, 2);
        $selSteps = array_rand($steps, 2);
        $sweep0 = array();
        $sweep0[0] = mt_rand(100, 700);
        $sweep0[1] = mt_rand(1500, 2500);
        $sweep1 = array();
        $sweep1[0] = mt_rand(500, 1000);
        $sweep1[1] = mt_rand(1200, 2000);

        if (mt_rand(0, 10) % 2 == 0) {
            $sweep0 = array_reverse($sweep0);
        }

        if (mt_rand(0, 10) % 2 == 0) {
            $sweep1 = array_reverse($sweep1);
        }

        $cmd = sprintf("%s -c %d -r %d -b %d -n -t wav - synth noise create vol 0.3 synth %.2f %s mix %d%s%d vol 0.3 synth %.2f %s fmod %d%s%d vol 0.3",
                       $this->sox_binary_path,
                       $numChannels,
                       $sampleRate,
                       $bitRate,
                       $duration,
                       $shapes[$selShapes[0]],
                       $sweep0[0],
                       $steps[$selSteps[0]],
                       $sweep0[1],
                       $duration,
                       $shapes[$selShapes[1]],
                       $sweep1[0],
                       $steps[$selSteps[1]],
                       $sweep1[1]
        );
        $data = `$cmd`;

        return $data;
    }
}

/**
 * Color object for Securimage CAPTCHA
 * @version 3.0
 * @since 2.0
 * @package Securimage
 * @subpackage classes
 *
 */
class Securimage_Color {
    /**
     * Red value (0-255)
     * @var int
     */
    public $r;

    /**
     * Gree value (0-255)
     * @var int
     */
    public $g;

    /**
     * Blue value (0-255)
     * @var int
     */
    public $b;

    /**
     * Create a new Securimage_Color object.
     * Constructor expects 1 or 3 arguments.
     * When passing a single argument, specify the color using HTML hex format.
     * When passing 3 arguments, specify each RGB component (from 0-255)
     * individually.
     * Examples:
     *     $color = new Securimage_Color('#0080FF');
     *     $color = new Securimage_Color(0, 128, 255);
     *
     * @param string $color The html color code to use
     * @throws Exception  If any color value is not valid
     */
    public function __construct($color = '#ffffff') {
        $args = func_get_args();

        if (sizeof($args) == 0) {
            $this->r = 255;
            $this->g = 255;
            $this->b = 255;
        } else {
            if (sizeof($args) == 1) {
                // set based on html code
                if (substr($color, 0, 1) == '#') {
                    $color = substr($color, 1);
                }

                if (strlen($color) != 3 && strlen($color) != 6) {
                    throw new InvalidArgumentException(
                        'Invalid HTML color code passed to Securimage_Color'
                    );
                }

                $this->constructHTML($color);
            } else {
                if (sizeof($args) == 3) {
                    $this->constructRGB($args[0], $args[1], $args[2]);
                } else {
                    throw new InvalidArgumentException(
                        'Securimage_Color constructor expects 0, 1 or 3 arguments; '.sizeof($args).' given'
                    );
                }
            }
        }
    }

    /**
     * Construct from an html hex color code
     * @param string $color
     */
    protected function constructHTML($color) {
        if (strlen($color) == 3) {
            $red = str_repeat(substr($color, 0, 1), 2);
            $green = str_repeat(substr($color, 1, 1), 2);
            $blue = str_repeat(substr($color, 2, 1), 2);
        } else {
            $red = substr($color, 0, 2);
            $green = substr($color, 2, 2);
            $blue = substr($color, 4, 2);
        }

        $this->r = hexdec($red);
        $this->g = hexdec($green);
        $this->b = hexdec($blue);
    }

    /**
     * Construct from an rgb triplet
     * @param int $red The red component, 0-255
     * @param int $green The green component, 0-255
     * @param int $blue The blue component, 0-255
     */
    protected function constructRGB($red, $green, $blue) {
        if ($red < 0) {
            $red = 0;
        }
        if ($red > 255) {
            $red = 255;
        }
        if ($green < 0) {
            $green = 0;
        }
        if ($green > 255) {
            $green = 255;
        }
        if ($blue < 0) {
            $blue = 0;
        }
        if ($blue > 255) {
            $blue = 255;
        }

        $this->r = $red;
        $this->g = $green;
        $this->b = $blue;
    }
}
