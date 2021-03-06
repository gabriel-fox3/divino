!(function (t, e) {
  if ("object" == typeof exports && "object" == typeof module) module.exports = e();
  else if ("function" == typeof define && define.amd) define([], e);
  else {
    var n = e();
    for (var r in n) ("object" == typeof exports ? exports : t)[r] = n[r];
  }
})(window, function () {
  return (function (t) {
    var e = {};
    function n(r) {
      if (e[r]) return e[r].exports;
      var o = (e[r] = { i: r, l: !1, exports: {} });
      return t[r].call(o.exports, o, o.exports, n), (o.l = !0), o.exports;
    }
    return (
      (n.m = t),
      (n.c = e),
      (n.d = function (t, e, r) {
        n.o(t, e) || Object.defineProperty(t, e, { enumerable: !0, get: r });
      }),
      (n.r = function (t) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, { value: "Module" }), Object.defineProperty(t, "__esModule", { value: !0 });
      }),
      (n.t = function (t, e) {
        if ((1 & e && (t = n(t)), 8 & e)) return t;
        if (4 & e && "object" == typeof t && t && t.__esModule) return t;
        var r = Object.create(null);
        if ((n.r(r), Object.defineProperty(r, "default", { enumerable: !0, value: t }), 2 & e && "string" != typeof t))
          for (var o in t)
            n.d(
              r,
              o,
              function (e) {
                return t[e];
              }.bind(null, o)
            );
        return r;
      }),
      (n.n = function (t) {
        var e =
          t && t.__esModule
            ? function () {
                return t.default;
              }
            : function () {
                return t;
              };
        return n.d(e, "a", e), e;
      }),
      (n.o = function (t, e) {
        return Object.prototype.hasOwnProperty.call(t, e);
      }),
      (n.p = "/dist/"),
      n((n.s = 0))
    );
  })([
    function (t, e, n) {
      "use strict";
      n.r(e),
        n.d(e, "htmlEditButton", function () {
          return s;
        });
      n(1);
      var r = (function () {
        function t(t, e) {
          for (var n = 0; n < e.length; n++) {
            var r = e[n];
            (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(t, r.key, r);
          }
        }
        return function (e, n, r) {
          return n && t(e.prototype, n), r && t(e, r), e;
        };
      })();
      function o(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function");
      }
      function i(t) {
        return document.createElement(t);
      }
      function a(t, e, n) {
        return t.setAttribute(e, n);
      }
      var l = !1,
        u = {
          prefixString: function () {
            return "</> quill-html-edit-button: ";
          },
          get log() {
            return l ? console.log.bind(console, this.prefixString()) : function () {};
          },
        },
        s = (function () {
          function t(e, n) {
            o(this, t), (l = n && n.debug), u.log("logging enabled");
            var r = e.getModule("toolbar");
            if (!r) throw new Error('quill.htmlEditButton requires the "toolbar" module to be included too');
            this.registerDivModule();
            var s = r.container,
              c = i("span");
            a(c, "class", "ql-formats");
            var f = i("button");
            (f.innerHTML = "&lt;&gt;"),
              (f.title = "Show HTML source"),
              (f.onclick = function (t) {
                t.preventDefault(),
                  (function (t, e) {
                    var n = t.container.querySelector(".ql-editor").innerHTML,
                      r = i("div"),
                      o = i("div"),
                      l = e.msg || 'Edit HTML here, when you click "OK" the quill editor\'s contents will be replaced',
                      s = e.cancelText || "Cancel",
                      c = e.okText || "Ok";
                    a(o, "class", "ql-html-overlayContainer"), a(r, "class", "ql-html-popupContainer");
                    var f = i("i");
                    a(f, "class", "ql-html-popupTitle"), (f.innerText = l);
                    var p = i("div");
                    p.appendChild(f), a(p, "class", "ql-html-textContainer");
                    var d = i("textarea");
                    a(d, "class", "ql-html-textArea"),
                      (d.value = (function (t) {
                        for (var e = " ".repeat(2), n = 0, r = null, o = null, i = "", a = 0; a <= t.length; a++) {
                          (r = t.substr(a, 1)), (o = t.substr(a + 1, 1));
                          var l = "<br>" === t.substr(a, 4),
                            s = "<" === r && "/" === o && !l;
                          l && ((i += "\n"), n--, (a += 4)),
                            "<" === r && "/" !== o && !l
                              ? ((i += "\n" + e.repeat(n)), n++)
                              : s
                              ? (--n < 0 && (n = 0), (i += "\n" + e.repeat(n)))
                              : " " === r && " " === o
                              ? (r = "")
                              : "\n" === r && "" === t.substr(a, t.substr(a).indexOf("<")).trim() && (r = ""),
                            (i += r);
                        }
                        return u.log("formatHTML", { before: t, after: i }), i;
                      })(n));
                    var h = i("button");
                    (h.innerHTML = s), a(h, "class", "ql-html-buttonCancel btn btn-light btn-square mr-3");
                    var b = i("button");
                    (b.innerHTML = c), a(b, "class", "ql-html-buttonOk btn btn-success btn-square");
                    var v = i("div");
                    a(v, "class", "ql-html-buttonGroup"),
                      v.appendChild(h),
                      v.appendChild(b),
                      p.appendChild(d),
                      p.appendChild(v),
                      r.appendChild(p),
                      o.appendChild(r),
                      document.body.appendChild(o),
                      (h.onclick = function () {
                        document.body.removeChild(o);
                      }),
                      (o.onclick = h.onclick),
                      (r.onclick = function (t) {
                        t.preventDefault(), t.stopPropagation();
                      }),
                      (b.onclick = function () {
                        var e = d.value
                          .split(/\r?\n/g)
                          .map(function (t) {
                            return t.trim();
                          })
                          .join("");
                        (t.container.querySelector(".ql-editor").innerHTML = e), document.body.removeChild(o);
                      });
                  })(e, n);
              }),
              c.appendChild(f),
              s.appendChild(c);
          }
          return (
            r(t, [
              {
                key: "registerDivModule",
                value: function () {
                  var t = Quill.import("blots/block"),
                    e = (function (t) {
                      function e() {
                        return (
                          o(this, e),
                          (function (t, e) {
                            if (!t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                            return !e || ("object" != typeof e && "function" != typeof e) ? t : e;
                          })(this, (e.__proto__ || Object.getPrototypeOf(e)).apply(this, arguments))
                        );
                      }
                      return (
                        (function (t, e) {
                          if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function, not " + typeof e);
                          (t.prototype = Object.create(e && e.prototype, { constructor: { value: t, enumerable: !1, writable: !0, configurable: !0 } })), e && (Object.setPrototypeOf ? Object.setPrototypeOf(t, e) : (t.__proto__ = e));
                        })(e, t),
                        e
                      );
                    })(t);
                  (e.tagName = "div"), (e.blotName = "div"), (e.allowedChildren = t.allowedChildren), e.allowedChildren.push(t), Quill.register(e);
                },
              },
            ]),
            t
          );
        })();
      (window.htmlEditButton = s), (e.default = s);
    },
    function (t, e, n) {
      var r = n(2);
      "string" == typeof r && (r = [[t.i, r, ""]]);
      var o = { hmr: !0, transform: void 0 };
      n(4)(r, o);
      r.locals && (t.exports = r.locals);
    },
    function (t, e, n) {
      (t.exports = n(3)(!1)).push([
        t.i,
        ".ql-html-overlayContainer {\r\n  background: #0000007d;\r\n  position: fixed;\r\n  top: 0;\r\n  left: 0;\r\n  right: 0;\r\n  bottom: 0;\r\n  z-index: 9999;\r\n}\r\n\r\n.ql-html-popupContainer {\r\n  background: #ddd;\r\n  position: absolute;\r\n  top: 5%;\r\n  left: 5%;\r\n  right: 5%;\r\n  bottom: 5%;\r\n  border-radius: 10px;\r\n}\r\n\r\n.ql-html-textContainer {\r\n  position: relative;\r\n  width: calc(100% - 40px);\r\n  height: calc(100% - 40px);\r\n  padding: 20px;\r\n}\r\n\r\n.ql-html-textArea {\r\n  position: absolute;\r\n  left: 15px;\r\n  width: calc(100% - 45px);\r\n  height: calc(100% - 116px);\r\n}\r\n\r\n.ql-html-buttonCancel {\r\n  margin-right: 20px;\r\n}\r\n\r\n.ql-html-popupTitle {\r\n  margin: 0;\r\n  display: block;\r\n}\r\n\r\n.ql-html-buttonGroup {\r\n  position: absolute;\r\n  bottom: 20px;\r\n  transform: scale(1.5);\r\n  left: calc(50% - 60px);\r\n}\r\n",
        "",
      ]);
    },
    function (t, e) {
      t.exports = function (t) {
        var e = [];
        return (
          (e.toString = function () {
            return this.map(function (e) {
              var n = (function (t, e) {
                var n = t[1] || "",
                  r = t[3];
                if (!r) return n;
                if (e && "function" == typeof btoa) {
                  var o = ((a = r), "/*# sourceMappingURL=data:application/json;charset=utf-8;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(a)))) + " */"),
                    i = r.sources.map(function (t) {
                      return "/*# sourceURL=" + r.sourceRoot + t + " */";
                    });
                  return [n].concat(i).concat([o]).join("\n");
                }
                var a;
                return [n].join("\n");
              })(e, t);
              return e[2] ? "@media " + e[2] + "{" + n + "}" : n;
            }).join("");
          }),
          (e.i = function (t, n) {
            "string" == typeof t && (t = [[null, t, ""]]);
            for (var r = {}, o = 0; o < this.length; o++) {
              var i = this[o][0];
              "number" == typeof i && (r[i] = !0);
            }
            for (o = 0; o < t.length; o++) {
              var a = t[o];
              ("number" == typeof a[0] && r[a[0]]) || (n && !a[2] ? (a[2] = n) : n && (a[2] = "(" + a[2] + ") and (" + n + ")"), e.push(a));
            }
          }),
          e
        );
      };
    },
    function (t, e, n) {
      var r,
        o,
        i = {},
        a =
          ((r = function () {
            return window && document && document.all && !window.atob;
          }),
          function () {
            return void 0 === o && (o = r.apply(this, arguments)), o;
          }),
        l = (function (t) {
          var e = {};
          return function (n) {
            if (void 0 === e[n]) {
              var r = t.call(this, n);
              if (r instanceof window.HTMLIFrameElement)
                try {
                  r = r.contentDocument.head;
                } catch (t) {
                  r = null;
                }
              e[n] = r;
            }
            return e[n];
          };
        })(function (t) {
          return document.querySelector(t);
        }),
        u = null,
        s = 0,
        c = [],
        f = n(5);
      function p(t, e) {
        for (var n = 0; n < t.length; n++) {
          var r = t[n],
            o = i[r.id];
          if (o) {
            o.refs++;
            for (var a = 0; a < o.parts.length; a++) o.parts[a](r.parts[a]);
            for (; a < r.parts.length; a++) o.parts.push(y(r.parts[a], e));
          } else {
            var l = [];
            for (a = 0; a < r.parts.length; a++) l.push(y(r.parts[a], e));
            i[r.id] = { id: r.id, refs: 1, parts: l };
          }
        }
      }
      function d(t, e) {
        for (var n = [], r = {}, o = 0; o < t.length; o++) {
          var i = t[o],
            a = e.base ? i[0] + e.base : i[0],
            l = { css: i[1], media: i[2], sourceMap: i[3] };
          r[a] ? r[a].parts.push(l) : n.push((r[a] = { id: a, parts: [l] }));
        }
        return n;
      }
      function h(t, e) {
        var n = l(t.insertInto);
        if (!n) throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
        var r = c[c.length - 1];
        if ("top" === t.insertAt) r ? (r.nextSibling ? n.insertBefore(e, r.nextSibling) : n.appendChild(e)) : n.insertBefore(e, n.firstChild), c.push(e);
        else if ("bottom" === t.insertAt) n.appendChild(e);
        else {
          if ("object" != typeof t.insertAt || !t.insertAt.before)
            throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
          var o = l(t.insertInto + " " + t.insertAt.before);
          n.insertBefore(e, o);
        }
      }
      function b(t) {
        if (null === t.parentNode) return !1;
        t.parentNode.removeChild(t);
        var e = c.indexOf(t);
        e >= 0 && c.splice(e, 1);
      }
      function v(t) {
        var e = document.createElement("style");
        return (t.attrs.type = "text/css"), m(e, t.attrs), h(t, e), e;
      }
      function m(t, e) {
        Object.keys(e).forEach(function (n) {
          t.setAttribute(n, e[n]);
        });
      }
      function y(t, e) {
        var n, r, o, i;
        if (e.transform && t.css) {
          if (!(i = e.transform(t.css))) return function () {};
          t.css = i;
        }
        if (e.singleton) {
          var a = s++;
          (n = u || (u = v(e))), (r = w.bind(null, n, a, !1)), (o = w.bind(null, n, a, !0));
        } else
          t.sourceMap && "function" == typeof URL && "function" == typeof URL.createObjectURL && "function" == typeof URL.revokeObjectURL && "function" == typeof Blob && "function" == typeof btoa
            ? ((n = (function (t) {
                var e = document.createElement("link");
                return (t.attrs.type = "text/css"), (t.attrs.rel = "stylesheet"), m(e, t.attrs), h(t, e), e;
              })(e)),
              (r = j.bind(null, n, e)),
              (o = function () {
                b(n), n.href && URL.revokeObjectURL(n.href);
              }))
            : ((n = v(e)),
              (r = C.bind(null, n)),
              (o = function () {
                b(n);
              }));
        return (
          r(t),
          function (e) {
            if (e) {
              if (e.css === t.css && e.media === t.media && e.sourceMap === t.sourceMap) return;
              r((t = e));
            } else o();
          }
        );
      }
      t.exports = function (t, e) {
        if ("undefined" != typeof DEBUG && DEBUG && "object" != typeof document) throw new Error("The style-loader cannot be used in a non-browser environment");
        ((e = e || {}).attrs = "object" == typeof e.attrs ? e.attrs : {}), e.singleton || "boolean" == typeof e.singleton || (e.singleton = a()), e.insertInto || (e.insertInto = "head"), e.insertAt || (e.insertAt = "bottom");
        var n = d(t, e);
        return (
          p(n, e),
          function (t) {
            for (var r = [], o = 0; o < n.length; o++) {
              var a = n[o];
              (l = i[a.id]).refs--, r.push(l);
            }
            t && p(d(t, e), e);
            for (o = 0; o < r.length; o++) {
              var l;
              if (0 === (l = r[o]).refs) {
                for (var u = 0; u < l.parts.length; u++) l.parts[u]();
                delete i[l.id];
              }
            }
          }
        );
      };
      var g,
        x =
          ((g = []),
          function (t, e) {
            return (g[t] = e), g.filter(Boolean).join("\n");
          });
      function w(t, e, n, r) {
        var o = n ? "" : r.css;
        // if (t.styleSheet) t.styleSheet.cssText = x(e, o);
        // else {
        //   var i = document.createTextNode(o),
        //     a = t.childNodes;
        //   a[e] && t.removeChild(a[e]), a.length ? t.insertBefore(i, a[e]) : t.appendChild(i);
        // }
      }
      function C(t, e) {
        var n = e.css,
          r = e.media;
        // if ((r && t.setAttribute("media", r), t.styleSheet)) t.styleSheet.cssText = n;
        // else {
        //   for (; t.firstChild; ) t.removeChild(t.firstChild);
        //   t.appendChild(document.createTextNode(n));
        // }
      }
      function j(t, e, n) {
        var r = n.css,
          o = n.sourceMap,
          i = void 0 === e.convertToAbsoluteUrls && o;
        (e.convertToAbsoluteUrls || i) && (r = f(r)), o && (r += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(o)))) + " */");
        var a = new Blob([r], { type: "text/css" }),
          l = t.href;
        (t.href = URL.createObjectURL(a)), l && URL.revokeObjectURL(l);
      }
    },
    function (t, e) {
      t.exports = function (t) {
        var e = "undefined" != typeof window && window.location;
        if (!e) throw new Error("fixUrls requires window.location");
        if (!t || "string" != typeof t) return t;
        var n = e.protocol + "//" + e.host,
          r = n + e.pathname.replace(/\/[^\/]*$/, "/");
        return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function (t, e) {
          var o,
            i = e
              .trim()
              .replace(/^"(.*)"$/, function (t, e) {
                return e;
              })
              .replace(/^'(.*)'$/, function (t, e) {
                return e;
              });
          return /^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(i) ? t : ((o = 0 === i.indexOf("//") ? i : 0 === i.indexOf("/") ? n + i : r + i.replace(/^\.\//, "")), "url(" + JSON.stringify(o) + ")");
        });
      };
    },
  ]);
});
