# jQuery Emoji Picker #

## Description ##

The top 3 things your users love:

1. Animated GIFs
2. Emojis
3. 🍩's

This JQuery Plugin allows you to add support for both Emojis and 🍩 to any text input on your web app.  With this plugin, your desktop users can enjoy the same great Emoji support your mobile users already have. All while eating a 🍩.

Want to see a [demo](http://wedgies.github.io/jquery-emoji-picker/demo.html)?

## Installation ##

You can install the assets via bower:

```bash
$ bower install jquery-emoji-picker
```

Make sure that jQuery is included in your `<head></head>` tag.  If you are not already including it, you can load it from jQuery's CDN:

```html
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
```

Add the following to your `<head></head>` tag:

```html
<link rel="stylesheet" type="text/css" href="css/jquery.emojipicker.css">
<script type="text/javascript" src="js/jquery.emojipicker.js"></script>

<!-- Emoji Data -->
<link rel="stylesheet" type="text/css" href="css/jquery.emojipicker.a.css">
<script type="text/javascript" src="js/jquery.emojis.js"></script>
```

The jQuery Emoji Picker has 3 icon sets to choose from - Apple, Twitter, and Google icons. The Apple icons are used by default. To instead use Twitter or Google icons, replace the stylesheet:

```html
<link rel="stylesheet" type="text/css" href="css/jquery.emojipicker.a.css">
```

with either Google:

```html
<link rel="stylesheet" type="text/css" href="css/jquery.emojipicker.g.css">
```

or Twitter:

```html
<link rel="stylesheet" type="text/css" href="css/jquery.emojipicker.tw.css">
```

Initialize the jQuery Emoji Picker by calling `emojiPicker` on an input element with optional parameters, described below:

```javascript
$('.question').emojiPicker({
  height: '300px',
  width:  '450px'
});
```

## Parameters ##

### width (int) ###
The width of the picker in pixels. Must be between 280-600px. Defaults to 280px if no width is specified.

### height (int) ###
The height of the picker in pixels. Must be between 100-350px. Defaults to 250px if no height is specified.

### fadeTime (int) ###
The amount of time in ms that it will take for the picker to fade in and out. Defaults to 100ms if no fadeTime is specified.

### iconColor (string) ###
The color of the smiley image that appears on the picker button. Acceptable values are ['white', 'black', 'gray', 'yellow']. Defaults to 'black' if no iconColor is specified.

### iconBackgroundColor (string) ###
The background color of the picker button. Any hex value is acceptable. Defaults to '#eee' if no iconBackgroundColor is specified.

### recentCount (int) ###
The number of emojis that should show in the 'Recently Used' section. Defaults to 36 if no recentCount is specified.

Note: 'Recently Used' will only show for the user if their browser supports HTML5 Local Storage.

### button (boolean) ###
Whether to show the emoji button on the input or not. Defaults to true. If you hide the button, you will probably need to trigger the emoji entry manually (see below).

### onShow (function) ###
Triggered once the emoji picker appears. `picker` (Object), `settings` (Object), and `isActive` (boolean) are returned. Example usage:

```javascript
$('#question').emojiPicker({
  onShow: function(picker, settings, isActive) {
  	...
  }
});
```

### onHide (function) ###
Triggered once the emoji picker disappears. `picker` (Object), `settings` (Object), and `isActive` (boolean) are returned. Example usage:

```javascript
$('#question').emojiPicker({
  onHide: function(picker, settings, isActive) {
  	...
  }
});
```

## Triggering Emoji Picker Manually ##

To trigger the button manually, you can call a jQuery function on the same element you bound it to.

```javascript
$('#question').emojiPicker('toggle');
```

You can see an example of this in the [demo](http://wedgies.github.io/jquery-emoji-picker/demo.html).

## Destroying the Emoji Picker ##

To remove the the emoji picker html and event listeners, simply call the emoji picker function with the `destroy` option:

```javascript
$('#question').emojiPicker('destroy');
```

An example of this can be found in the [demo](http://wedgies.github.io/jquery-emoji-picker/demo.html).



## Notes ##

jQuery Emoji Picker is disabled for mobile devices, which already support the emoji keyboard.

## Attribution ##

The jQuery Emoji Picker would not be here without the CSS and image data generated from Wemoji:
[https://github.com/wedgies/wemoji](https://github.com/wedgies/wemoji)

Apple Emoji: Copyright (C) Apple Inc. - OS X 10.10.3
Android Emoji: Copyright (C) The Android Open Source Project - Lollipop
Twitter Emoji Copyright (C) Twitter, Inc. - The original release

## License ##

The jQuery Emoji Picker is licensed under ISC.

## About ##

The jQuery Emoji Picker was developed by the team at [Wedgies](http://www.wedgies.com).

Wedgies is a digital survey platform that gives media, journalists and brands in-line survey capabilities inside social media, their website, and their apps — where they can collect millions of opinions from their readers and users. Wedgies is the leading social survey platform that enables publishers to collect survey respondents directly within social media streams.

Wedgies is backed by an all-star list of investors including Vegas Tech Fund, Greycroft, Advancit Capital, MESA Ventures, Knight Foundation, kbs+ Ventures, Battle Born Ventures, Twilio, 500 Startups and SV Angel.

Wedgies' founding team consists of top talent in developer tools and community management with experience that includes Zappos.com, Overstock.com, and Backcountry.com.

[![Built with Wedgies](https://d3v9r9uda02hel.cloudfront.net/production/1.55.17/img/built-with-wedgies.png)](http://wedgies.com)
