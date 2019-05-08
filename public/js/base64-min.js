/**
 *    createname：雨中磐石
 *    homeurl：http://www.rockoa.com/
 *    Copyright (c) 2016 rainrock
 *    Date:2016-01-01
 */
function Jiami() {
    var c, d, e, f, a = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
        b = "abcdefghijklmnopqrstuvwxyz";
    this.setJmstr = function (a) {
        var e, c = {
            "?": "v",
            '"': "s",
            "[": "p",
            "^": "g",
            _: "l",
            "(": "j",
            "-": "x",
            ")": "k",
            "+": "m",
            "~": "a",
            ":": "w",
            $: "e",
            "{": "n",
            "&": "h",
            "<": "t",
            "}": "o",
            "@": "c",
            ".": "z",
            "#": "d",
            "%": "f",
            "!": "b",
            "]": "q",
            "*": "i",
            ";": "r",
            "=": "y",
            ">": "u"
        }, d = a.length, f = "";
        for (e = 0; d > e; e++) f += c[a.substr(e, 1)];
        return b = f, f
    }, this.base64encode = function (b) {
        var c, d, f, g, h, i, j, k, l, m;
        if ("" == b || null == b) return "";
        for (c = "", m = 0, b = e(b); m < b.length;) d = b.charCodeAt(m++), f = b.charCodeAt(m++), g = b.charCodeAt(m++), h = d >> 2, i = (3 & d) << 4 | f >> 4, j = (15 & f) << 2 | g >> 6, k = 63 & g, isNaN(f) ? j = k = 64 : isNaN(g) && (k = 64), c = c + a.charAt(h) + a.charAt(i) + a.charAt(j) + a.charAt(k);
        return l = c, l = l.replace(/\+/g, "!"), l = l.replace(/\//g, "."), l = l.replace(/\=/g, ":")
    }, this.encrypt = function (a, b) {
        if (!a) return "";
        var d = this.base64encode(a);
        return d = c(d, b)
    }, this.uncrypt = function (a, b) {
        if (!a) return "";
        var c = d(a, b);
        return c = this.base64decode(c)
    }, c = function (a, c) {
        var g, h, j, d = "", e = "", f = a.length, i = b, k = parseInt(14 * Math.random()) + 1;
        for (c && 26 == c.length && (i = c), 10 == k && k++, g = 0; f > g; g++) for (e = a.charCodeAt(g).toString(), d += "0", h = 0; h < e.length; h++) j = parseInt(e.substr(h, 1)) + k, d += i.substr(j, 1);
        return "" != d && (d = d.substr(1), d += "0" + k), d
    }, d = function (a, c) {
        a = a.replace(/[^a-z0-9]/g, "");
        var f, g, j, k, l, m, n, d = "", e = "", h = b, i = {};
        for (c && 26 == c.length && (h = c), j = 0; j < h.length; j++) i[h.substr(j, 1)] = j;
        for (l = a.split("0"), n = l.length, m = parseInt(l[n - 1]), j = 0; n - 1 > j; j++) {
            for (e = l[j], f = "", k = 0; k < e.length; k++) g = parseInt(i[e.substr(k, 1)]) - m, f += g.toString();
            f = parseInt(f), d += String.fromCharCode(f).toString()
        }
        return d
    }, this.base64decode = function (b) {
        var c, d, e, g, h, i, j, k, l;
        if ("" == b || null == b) return "";
        for (c = "", l = 0, b = b.replace(/\!/g, "+"), b = b.replace(/\./g, "/"), b = b.replace(/\:/g, "="), b = b.replace(/[^A-Za-z0-9\+\/\=]/g, ""); l < b.length;) h = a.indexOf(b.charAt(l++)), i = a.indexOf(b.charAt(l++)), j = a.indexOf(b.charAt(l++)), k = a.indexOf(b.charAt(l++)), d = h << 2 | i >> 4, e = (15 & i) << 4 | j >> 2, g = (3 & j) << 6 | k, c += String.fromCharCode(d), 64 != j && (c += String.fromCharCode(e)), 64 != k && (c += String.fromCharCode(g));
        return c = f(c)
    }, e = function (a) {
        var b, c, d;
        for (a = a.replace(/\r\n/g, "\n"), b = "", c = 0; c < a.length; c++) d = a.charCodeAt(c), 128 > d ? b += String.fromCharCode(d) : d > 127 && 2048 > d ? (b += String.fromCharCode(192 | d >> 6), b += String.fromCharCode(128 | 63 & d)) : (b += String.fromCharCode(224 | d >> 12), b += String.fromCharCode(128 | 63 & d >> 6), b += String.fromCharCode(128 | 63 & d));
        return b
    }, f = function (a) {
        for (var b = "", c = 0, d = c1 = c2 = 0; c < a.length;) d = a.charCodeAt(c), 128 > d ? (b += String.fromCharCode(d), c++) : d > 191 && 224 > d ? (c2 = a.charCodeAt(c + 1), b += String.fromCharCode((31 & d) << 6 | 63 & c2), c += 2) : (c2 = a.charCodeAt(c + 1), c3 = a.charCodeAt(c + 2), b += String.fromCharCode((15 & d) << 12 | (63 & c2) << 6 | 63 & c3), c += 3);
        return b
    }
}

var jm = new Jiami;
!function (n) {
    "use strict";

    function t(n, t) {
        var r = (65535 & n) + (65535 & t);
        return (n >> 16) + (t >> 16) + (r >> 16) << 16 | 65535 & r
    }

    function r(n, t) {
        return n << t | n >>> 32 - t
    }

    function e(n, e, o, u, c, f) {
        return t(r(t(t(e, n), t(u, f)), c), o)
    }

    function o(n, t, r, o, u, c, f) {
        return e(t & r | ~t & o, n, t, u, c, f)
    }

    function u(n, t, r, o, u, c, f) {
        return e(t & o | r & ~o, n, t, u, c, f)
    }

    function c(n, t, r, o, u, c, f) {
        return e(t ^ r ^ o, n, t, u, c, f)
    }

    function f(n, t, r, o, u, c, f) {
        return e(r ^ (t | ~o), n, t, u, c, f)
    }

    function i(n, r) {
        n[r >> 5] |= 128 << r % 32, n[14 + (r + 64 >>> 9 << 4)] = r;
        var e, i, a, d, h, l = 1732584193, g = -271733879, v = -1732584194, m = 271733878;
        for (e = 0; e < n.length; e += 16) i = l, a = g, d = v, h = m, g = f(g = f(g = f(g = f(g = c(g = c(g = c(g = c(g = u(g = u(g = u(g = u(g = o(g = o(g = o(g = o(g, v = o(v, m = o(m, l = o(l, g, v, m, n[e], 7, -680876936), g, v, n[e + 1], 12, -389564586), l, g, n[e + 2], 17, 606105819), m, l, n[e + 3], 22, -1044525330), v = o(v, m = o(m, l = o(l, g, v, m, n[e + 4], 7, -176418897), g, v, n[e + 5], 12, 1200080426), l, g, n[e + 6], 17, -1473231341), m, l, n[e + 7], 22, -45705983), v = o(v, m = o(m, l = o(l, g, v, m, n[e + 8], 7, 1770035416), g, v, n[e + 9], 12, -1958414417), l, g, n[e + 10], 17, -42063), m, l, n[e + 11], 22, -1990404162), v = o(v, m = o(m, l = o(l, g, v, m, n[e + 12], 7, 1804603682), g, v, n[e + 13], 12, -40341101), l, g, n[e + 14], 17, -1502002290), m, l, n[e + 15], 22, 1236535329), v = u(v, m = u(m, l = u(l, g, v, m, n[e + 1], 5, -165796510), g, v, n[e + 6], 9, -1069501632), l, g, n[e + 11], 14, 643717713), m, l, n[e], 20, -373897302), v = u(v, m = u(m, l = u(l, g, v, m, n[e + 5], 5, -701558691), g, v, n[e + 10], 9, 38016083), l, g, n[e + 15], 14, -660478335), m, l, n[e + 4], 20, -405537848), v = u(v, m = u(m, l = u(l, g, v, m, n[e + 9], 5, 568446438), g, v, n[e + 14], 9, -1019803690), l, g, n[e + 3], 14, -187363961), m, l, n[e + 8], 20, 1163531501), v = u(v, m = u(m, l = u(l, g, v, m, n[e + 13], 5, -1444681467), g, v, n[e + 2], 9, -51403784), l, g, n[e + 7], 14, 1735328473), m, l, n[e + 12], 20, -1926607734), v = c(v, m = c(m, l = c(l, g, v, m, n[e + 5], 4, -378558), g, v, n[e + 8], 11, -2022574463), l, g, n[e + 11], 16, 1839030562), m, l, n[e + 14], 23, -35309556), v = c(v, m = c(m, l = c(l, g, v, m, n[e + 1], 4, -1530992060), g, v, n[e + 4], 11, 1272893353), l, g, n[e + 7], 16, -155497632), m, l, n[e + 10], 23, -1094730640), v = c(v, m = c(m, l = c(l, g, v, m, n[e + 13], 4, 681279174), g, v, n[e], 11, -358537222), l, g, n[e + 3], 16, -722521979), m, l, n[e + 6], 23, 76029189), v = c(v, m = c(m, l = c(l, g, v, m, n[e + 9], 4, -640364487), g, v, n[e + 12], 11, -421815835), l, g, n[e + 15], 16, 530742520), m, l, n[e + 2], 23, -995338651), v = f(v, m = f(m, l = f(l, g, v, m, n[e], 6, -198630844), g, v, n[e + 7], 10, 1126891415), l, g, n[e + 14], 15, -1416354905), m, l, n[e + 5], 21, -57434055), v = f(v, m = f(m, l = f(l, g, v, m, n[e + 12], 6, 1700485571), g, v, n[e + 3], 10, -1894986606), l, g, n[e + 10], 15, -1051523), m, l, n[e + 1], 21, -2054922799), v = f(v, m = f(m, l = f(l, g, v, m, n[e + 8], 6, 1873313359), g, v, n[e + 15], 10, -30611744), l, g, n[e + 6], 15, -1560198380), m, l, n[e + 13], 21, 1309151649), v = f(v, m = f(m, l = f(l, g, v, m, n[e + 4], 6, -145523070), g, v, n[e + 11], 10, -1120210379), l, g, n[e + 2], 15, 718787259), m, l, n[e + 9], 21, -343485551), l = t(l, i), g = t(g, a), v = t(v, d), m = t(m, h);
        return [l, g, v, m]
    }

    function a(n) {
        var t, r = "", e = 32 * n.length;
        for (t = 0; t < e; t += 8) r += String.fromCharCode(n[t >> 5] >>> t % 32 & 255);
        return r
    }

    function d(n) {
        var t, r = [];
        for (r[(n.length >> 2) - 1] = void 0, t = 0; t < r.length; t += 1) r[t] = 0;
        var e = 8 * n.length;
        for (t = 0; t < e; t += 8) r[t >> 5] |= (255 & n.charCodeAt(t / 8)) << t % 32;
        return r
    }

    function h(n) {
        return a(i(d(n), 8 * n.length))
    }

    function l(n, t) {
        var r, e, o = d(n), u = [], c = [];
        for (u[15] = c[15] = void 0, o.length > 16 && (o = i(o, 8 * n.length)), r = 0; r < 16; r += 1) u[r] = 909522486 ^ o[r], c[r] = 1549556828 ^ o[r];
        return e = i(u.concat(d(t)), 512 + 8 * t.length), a(i(c.concat(e), 640))
    }

    function g(n) {
        var t, r, e = "";
        for (r = 0; r < n.length; r += 1) t = n.charCodeAt(r), e += "0123456789abcdef".charAt(t >>> 4 & 15) + "0123456789abcdef".charAt(15 & t);
        return e
    }

    function v(n) {
        return unescape(encodeURIComponent(n))
    }

    function m(n) {
        return h(v(n))
    }

    function p(n) {
        return g(m(n))
    }

    function s(n, t) {
        return l(v(n), v(t))
    }

    function C(n, t) {
        return g(s(n, t))
    }

    function A(n, t, r) {
        return t ? r ? s(t, n) : C(t, n) : r ? m(n) : p(n)
    }

    "function" == typeof define && define.amd ? define(function () {
        return A
    }) : "object" == typeof module && module.exports ? module.exports = A : n.md5 = A
}(this);