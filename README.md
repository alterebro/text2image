# Text to Image Generator. [text2image.moro.es](https://text2image.moro.es)

## Convert any String to Gradient Image

Create beautiful gradient abstract images using your own string as seed. Insert your text and generate an image based on its MD5 hash value.

**&mdash; Project URL : [https://text2image.moro.es](https://text2image.moro.es)**


**text2image** is a small Web Application which converts any string in an abstract gradient image having as a constructor the MD5 hash of the text input, so basically it could be also described as the MD5 visualization of a string.

The project is built on `JavaScript + Canvas` and it also has a fallback written on `PHP + GD Library` for those who don't have JavaScript enabled on their browsers.

This is an entry for the [10k Apart](https://a-k-apart.com/) contest, the index file has been minified and gzipped, all the styles and scripts have been included within this file to avoid extra request and increase speed, the whole data transfered by the client is as low as 5.7KB.

## How does it work

**text2image** takes the input string and generates the [MD5](https://en.wikipedia.org/wiki/MD5) hash value of this string. A MD5 hash consists of 32 hexadecimal characters string, this string gets splitted in 6 chunks, the first 4 will be the ones used to create the radial gradients, the 5th will be the background color and the last one will set the opacity of the background color.

e.g. we are going to use the string *'Hello world'*

- We create the md5 of the string `md5('Hello world')`
- Hash value is : `3e25960a79dbc69b674cd4ec67a72c62`
- Hash value chunks are then : `3e2596`, `0a79db`, `c69b67`, `4cd4ec`, `67a72c`, and `62`
- We use for the radial gradients chunks 1 to 4 as a base HTML hexadecimal color : **`#3e2596`, `#0a79db`, `#c69b67`, `#4cd4ec`**.
- 5th block is the background color: `#67a72c`.
- 6th block is the alpha opacity (hex): `62`. This value converted in decimal is 98 (over 255) or 38 (over 100).
- Finally we have the background which is **`#67a72c`** with a **`0.38`** opacity.

Now that we have all the colors we can create the image:

| #    | Step  | Image |
| ---- | ----- | ----- |
| #01 | We create an empty canvas. | <img src="http://i.imgur.com/JKaEatM.png" alt="Step #01" width="80" /> |
| #02 | Background color **`#67a72c`** with a **`38%`** alpha channel. | <img src="http://i.imgur.com/OjKocwQ.png" alt="Step #02" width="80" /> |
| #03 | First circle radial gradient from **TOP LEFT** with color **`#3e2596`** to transparent. |<img src="http://i.imgur.com/0YIlVAS.png" alt="Step #03" width="80" /> |
| #04 | Second circle radial gradient from **TOP RIGHT** with color **`#0a79db`** to transparent. |<img src="http://i.imgur.com/NFHjE8u.png" alt="Step #04" width="80" /> |
| #05 | Third circle radial gradient from **BOTTOM RIGHT** with color **`#c69b67`** to transparent. |<img src="http://i.imgur.com/TOWqggY.png" alt="Step #05" width="80" /> |
| #06 | Fourth circle radial gradient from **BOTTOM LEFT** with color **`#4cd4ec`** to transparent. |<img src="http://i.imgur.com/RoFwwDw.png" alt="Step #06" width="80" /> |

#### Output Image for 'Hello world' :

![text2image](http://i.imgur.com/RoFwwDw.png)


## Credits

**text2image** uses [JavaScript-MD5](https://github.com/blueimp/JavaScript-MD5), a MIT licensed JavaScript MD5 implementation made by Sebastian Tschan ([@blueimp](https://github.com/blueimp)) and it was inspired by a [Tim Pietrusky](https://twitter.com/TimPietrusky) small project: [Random string to CSS color](http://randomstringtocsscolor.com/) which was built using as a source a stackoverflow answer derived from the question: **[Why does HTML think “chucknorris” is a color?](http://stackoverflow.com/questions/8318911/why-does-html-think-chucknorris-is-a-color)**

## License

#### The MIT License (MIT)
Copyright &copy; 2016 Jorge Moreno ( [moro.es](http://moro.es), [@alterebro](https://twitter.com/alterebro) )

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

---

 &mdash; **[@alterebro](https://twitter.com/alterebro)**
