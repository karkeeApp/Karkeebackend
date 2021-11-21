/*!
 * froala_editor v3.2.1 (https://www.froala.com/wysiwyg-editor)
 * License https://froala.com/wysiwyg-editor/terms/
 * Copyright 2014-2020 Froala Labs
 */

!(function (e, t) {
    "object" == typeof exports && "undefined" != typeof module ? t(require("froala-editor")) : "function" == typeof define && define.amd ? define(["froala-editor"], t) : t(e.FroalaEditor);
})(this, function (Le) {
    "use strict";
    function _e(e) {
        return (_e =
            "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                ? function (e) {
                      return typeof e;
                  }
                : function (e) {
                      return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                  })(e);
    }
    (Le = Le && Le.hasOwnProperty("default") ? Le["default"] : Le),
        Object.assign(Le.POPUP_TEMPLATES, { "image.insert": "[_BUTTONS_][_UPLOAD_LAYER_][_BY_URL_LAYER_][_PROGRESS_BAR_]", "image.edit": "[_BUTTONS_]", "image.alt": "[_BUTTONS_][_ALT_LAYER_]", "image.size": "[_BUTTONS_][_SIZE_LAYER_]" }),
        Object.assign(Le.DEFAULTS, {
            imageInsertButtons: ["imageBack", "|", "imageUpload", "imageByURL"],
            imageEditButtons: ["imageReplace", "imageAlign", "imageCaption", "imageRemove", "imageLink", "linkOpen", "linkEdit", "linkRemove", "-", "imageDisplay", "imageStyle", "imageAlt", "imageSize"],
            imageAltButtons: ["imageBack", "|"],
            imageSizeButtons: ["imageBack", "|"],
            imageUpload: !0,
            imageUploadURL: null,
            imageCORSProxy: "https://cors-anywhere.froala.com",
            imageUploadRemoteUrls: !0,
            imageUploadParam: "file",
            imageUploadParams: {},
            imageUploadToS3: !1,
            imageUploadToAzure: !1,
            imageUploadMethod: "POST",
            imageMaxSize: 10485760,
            imageAllowedTypes: ["jpeg", "jpg", "png", "gif", "webp"],
            imageResize: !0,
            imageResizeWithPercent: !1,
            imageRoundPercent: !1,
            imageDefaultWidth: 300,
            imageDefaultAlign: "center",
            imageDefaultDisplay: "block",
            imageSplitHTML: !1,
            imageStyles: { "fr-rounded": "Rounded", "fr-bordered": "Bordered", "fr-shadow": "Shadow" },
            imageMove: !0,
            imageMultipleStyles: !0,
            imageTextNear: !0,
            imagePaste: !0,
            imagePasteProcess: !1,
            imageMinWidth: 16,
            imageOutputSize: !1,
            imageDefaultMargin: 5,
            imageAddNewLine: !1,
        }),
        (Le.PLUGINS.image = function (A) {
            var C,
                l,
                p,
                f,
                s,
                a,
                S = A.$,
                E = "https://i.froala.com/upload",
                t = !1,
                i = 1,
                c = 2,
                d = 3,
                m = 4,
                R = 5,
                U = 6,
                n = {};
            function g() {
                var e = A.popups.get("image.insert").find(".fr-image-by-url-layer input");
                e.val(""), C && e.val(C.attr("src")), e.trigger("change");
            }
            function r() {
                var e = A.popups.get("image.edit");
                if ((e || (e = P()), e)) {
                    var t = we();
                    Ae() && (t = t.find(".fr-img-wrap")), A.popups.setContainer("image.edit", A.$sc), A.popups.refresh("image.edit");
                    var a = t.offset().left + t.outerWidth() / 2,
                        i = t.offset().top + t.outerHeight();
                    C.hasClass("fr-uploading") ? I() : A.popups.show("image.edit", a, i, t.outerHeight(), !0);
                }
            }
            function u() {
                z();
            }
            function o(e) {
                0 < e.parents(".fr-img-caption").length && (e = e.parents(".fr-img-caption").first());
                var t = e.hasClass("fr-dib") ? "block" : e.hasClass("fr-dii") ? "inline" : null,
                    a = e.hasClass("fr-fil") ? "left" : e.hasClass("fr-fir") ? "right" : ue(e);
                me(e, t, a), e.removeClass("fr-dib fr-dii fr-fir fr-fil");
            }
            function h() {
                for (var e, t = "IMG" == A.el.tagName ? [A.el] : A.el.querySelectorAll("img"), a = 0; a < t.length; a++) {
                    var i = S(t[a]);
                    !A.opts.htmlUntouched && A.opts.useClasses
                        ? ((A.opts.imageDefaultAlign || A.opts.imageDefaultDisplay) &&
                              (0 < (e = i).parents(".fr-img-caption").length && (e = e.parents(".fr-img-caption").first()),
                              e.hasClass("fr-dii") ||
                                  e.hasClass("fr-dib") ||
                                  (e.addClass("fr-fi".concat(ue(e)[0])),
                                  e.addClass("fr-di".concat(he(e)[0])),
                                  e.css("margin", ""),
                                  e.css("float", ""),
                                  e.css("display", ""),
                                  e.css("z-index", ""),
                                  e.css("position", ""),
                                  e.css("overflow", ""),
                                  e.css("vertical-align", ""))),
                          A.opts.imageTextNear || (0 < i.parents(".fr-img-caption").length ? i.parents(".fr-img-caption").first().removeClass("fr-dii").addClass("fr-dib") : i.removeClass("fr-dii").addClass("fr-dib")))
                        : A.opts.htmlUntouched || A.opts.useClasses || ((A.opts.imageDefaultAlign || A.opts.imageDefaultDisplay) && o(i)),
                        A.opts.iframe && i.on("load", A.size.syncIframe);
                }
            }
            function v(e) {
                void 0 === e && (e = !0);
                var t,
                    a = Array.prototype.slice.call(A.el.querySelectorAll("img")),
                    i = [];
                for (t = 0; t < a.length; t++)
                    if (
                        (i.push(a[t].getAttribute("src")),
                        S(a[t]).toggleClass("fr-draggable", A.opts.imageMove),
                        "" === a[t].getAttribute("class") && a[t].removeAttribute("class"),
                        "" === a[t].getAttribute("style") && a[t].removeAttribute("style"),
                        a[t].parentNode && a[t].parentNode.parentNode && A.node.hasClass(a[t].parentNode.parentNode, "fr-img-caption"))
                    ) {
                        var n = a[t].parentNode.parentNode;
                        A.browser.mozilla || n.setAttribute("contenteditable", !1), n.setAttribute("draggable", !1), n.classList.add("fr-draggable");
                        var r = a[t].nextSibling;
                        r && !A.browser.mozilla && r.setAttribute("contenteditable", !0);
                    }
                if (s) for (t = 0; t < s.length; t++) i.indexOf(s[t].getAttribute("src")) < 0 && A.events.trigger("image.removed", [S(s[t])]);
                if (s && e) {
                    var o = [];
                    for (t = 0; t < s.length; t++) o.push(s[t].getAttribute("src"));
                    for (t = 0; t < a.length; t++) o.indexOf(a[t].getAttribute("src")) < 0 && A.events.trigger("image.loaded", [S(a[t])]);
                }
                s = a;
            }
            function x() {
                if (
                    (l ||
                        (function o() {
                            var e;
                            A.shared.$image_resizer
                                ? ((l = A.shared.$image_resizer),
                                  (f = A.shared.$img_overlay),
                                  A.events.on(
                                      "destroy",
                                      function () {
                                          S("body").first().append(l.removeClass("fr-active"));
                                      },
                                      !0
                                  ))
                                : ((A.shared.$image_resizer = S(document.createElement("div")).attr("class", "fr-image-resizer")),
                                  (l = A.shared.$image_resizer),
                                  A.events.$on(
                                      l,
                                      "mousedown",
                                      function (e) {
                                          e.stopPropagation();
                                      },
                                      !0
                                  ),
                                  A.opts.imageResize &&
                                      (l.append(b("nw") + b("ne") + b("sw") + b("se")),
                                      (A.shared.$img_overlay = S(document.createElement("div")).attr("class", "fr-image-overlay")),
                                      (f = A.shared.$img_overlay),
                                      (e = l.get(0).ownerDocument),
                                      S(e).find("body").first().append(f)));
                            A.events.on(
                                "shared.destroy",
                                function () {
                                    l.html("").removeData().remove(), (l = null), A.opts.imageResize && (f.remove(), (f = null));
                                },
                                !0
                            ),
                                A.helpers.isMobile() ||
                                    A.events.$on(S(A.o_win), "resize", function () {
                                        C && !C.hasClass("fr-uploading") ? fe(!0) : C && (x(), ve(), I(!1));
                                    });
                            if (A.opts.imageResize) {
                                (e = l.get(0).ownerDocument),
                                    A.events.$on(l, A._mousedown, ".fr-handler", w),
                                    A.events.$on(S(e), A._mousemove, D),
                                    A.events.$on(S(e.defaultView || e.parentWindow), A._mouseup, T),
                                    A.events.$on(f, "mouseleave", T);
                                var i = 1,
                                    n = null,
                                    r = 0;
                                A.events.on(
                                    "keydown",
                                    function (e) {
                                        if (C) {
                                            var t = -1 != navigator.userAgent.indexOf("Mac OS X") ? e.metaKey : e.ctrlKey,
                                                a = e.which;
                                            (a !== n || 200 < e.timeStamp - r) && (i = 1),
                                                (a == Le.KEYCODE.EQUALS || (A.browser.mozilla && a == Le.KEYCODE.FF_EQUALS)) && t && !e.altKey
                                                    ? (i = ee.call(this, e, 1, 1, i))
                                                    : (a == Le.KEYCODE.HYPHEN || (A.browser.mozilla && a == Le.KEYCODE.FF_HYPHEN)) && t && !e.altKey
                                                    ? (i = ee.call(this, e, 2, -1, i))
                                                    : A.keys.ctrlKey(e) || a != Le.KEYCODE.ENTER || (C.before("<br>"), O(C)),
                                                (n = a),
                                                (r = e.timeStamp);
                                        }
                                    },
                                    !0
                                ),
                                    A.events.on("keyup", function () {
                                        i = 1;
                                    });
                            }
                        })(),
                    !C)
                )
                    return !1;
                var e = A.$wp || A.$sc;
                e.append(l), l.data("instance", A);
                var t = e.scrollTop() - ("static" != e.css("position") ? e.offset().top : 0),
                    a = e.scrollLeft() - ("static" != e.css("position") ? e.offset().left : 0);
                (a -= A.helpers.getPX(e.css("border-left-width"))), (t -= A.helpers.getPX(e.css("border-top-width"))), A.$el.is("img") && A.$sc.is("body") && (a = t = 0);
                var i = we();
                Ae() && (i = i.find(".fr-img-wrap"));
                var n = 0,
                    r = 0;
                A.opts.iframe && ((n = A.helpers.getPX(A.$wp.find(".fr-iframe").css("padding-top"))), (r = A.helpers.getPX(A.$wp.find(".fr-iframe").css("padding-left")))),
                    l
                        .css("top", (A.opts.iframe ? i.offset().top + n : i.offset().top + t) - 1)
                        .css("left", (A.opts.iframe ? i.offset().left + r : i.offset().left + a) - 1)
                        .css("width", i.get(0).getBoundingClientRect().width)
                        .css("height", i.get(0).getBoundingClientRect().height)
                        .addClass("fr-active");
            }
            function b(e) {
                return '<div class="fr-handler fr-h'.concat(e, '"></div>');
            }
            function y(e) {
                Ae() ? C.parents(".fr-img-caption").css("width", e) : C.css("width", e);
            }
            function w(e) {
                if (!A.core.sameInstance(l)) return !0;
                if ((e.preventDefault(), e.stopPropagation(), A.$el.find("img.fr-error").left)) return !1;
                A.undo.canDo() || A.undo.saveStep();
                var t = e.pageX || e.originalEvent.touches[0].pageX;
                if ("mousedown" == e.type) {
                    var a = A.$oel.get(0).ownerDocument,
                        i = a.defaultView || a.parentWindow,
                        n = !1;
                    try {
                        n = i.location != i.parent.location && !(i.$ && i.$.FE);
                    } catch (s) {}
                    n && i.frameElement && (t += A.helpers.getPX(S(i.frameElement).offset().left) + i.frameElement.clientLeft);
                }
                (p = S(this)).data("start-x", t), p.data("start-width", C.width()), p.data("start-height", C.height());
                var r = C.width();
                if (A.opts.imageResizeWithPercent) {
                    var o = C.parentsUntil(A.$el, A.html.blockTagsQuery()).get(0) || A.el;
                    r = ((r / S(o).outerWidth()) * 100).toFixed(2) + "%";
                }
                y(r), f.show(), A.popups.hideAll(), de();
            }
            function D(e) {
                if (!A.core.sameInstance(l)) return !0;
                var t;
                if (p && C) {
                    if ((e.preventDefault(), A.$el.find("img.fr-error").left)) return !1;
                    var a = e.pageX || (e.originalEvent.touches ? e.originalEvent.touches[0].pageX : null);
                    if (!a) return !1;
                    var i = a - p.data("start-x"),
                        n = p.data("start-width");
                    if (((p.hasClass("fr-hnw") || p.hasClass("fr-hsw")) && (i = 0 - i), A.opts.imageResizeWithPercent)) {
                        var r = C.parentsUntil(A.$el, A.html.blockTagsQuery()).get(0) || A.el;
                        (n = (((n + i) / S(r).outerWidth()) * 100).toFixed(2)),
                            A.opts.imageRoundPercent && (n = Math.round(n)),
                            y("".concat(n, "%")),
                            (t = Ae() ? ((A.helpers.getPX(C.parents(".fr-img-caption").css("width")) / S(r).outerWidth()) * 100).toFixed(2) : ((A.helpers.getPX(C.css("width")) / S(r).outerWidth()) * 100).toFixed(2)) === n ||
                                A.opts.imageRoundPercent ||
                                y("".concat(t, "%")),
                            C.css("height", "").removeAttr("height");
                    } else
                        n + i >= A.opts.imageMinWidth && (y(n + i), (t = Ae() ? A.helpers.getPX(C.parents(".fr-img-caption").css("width")) : A.helpers.getPX(C.css("width")))),
                            t !== n + i && y(t),
                            ((C.attr("style") || "").match(/(^height:)|(; *height:)/) || C.attr("height")) && (C.css("height", (p.data("start-height") * C.width()) / p.data("start-width")), C.removeAttr("height"));
                    x(), A.events.trigger("image.resize", [ye()]);
                }
            }
            function T(e) {
                if (!A.core.sameInstance(l)) return !0;
                if (p && C) {
                    if ((e && e.stopPropagation(), A.$el.find("img.fr-error").left)) return !1;
                    (p = null), f.hide(), x(), r(), A.undo.saveStep(), A.events.trigger("image.resizeEnd", [ye()]);
                } else l.removeClass("fr-active");
            }
            function $(e, t, a) {
                A.edit.on(),
                    C && C.addClass("fr-error"),
                    n[e] ? B(A.language.translate(n[e])) : B(A.language.translate("Something went wrong. Please try again.")),
                    !C && a && te(a),
                    A.events.trigger("image.error", [{ code: e, message: n[e] }, t, a]);
            }
            function P(e) {
                if (e)
                    return (
                        A.$wp &&
                            A.events.$on(A.$wp, "scroll.image-edit", function () {
                                C && A.popups.isVisible("image.edit") && (A.events.disableBlur(), r());
                            }),
                        !0
                    );
                var t = "";
                if (0 < A.opts.imageEditButtons.length) {
                    var a = { buttons: (t += '<div class="fr-buttons"> \n        '.concat(A.button.buildList(A.opts.imageEditButtons), "\n        </div>")) };
                    return A.popups.create("image.edit", a);
                }
                return !1;
            }
            function I(e) {
                var t = A.popups.get("image.insert");
                if ((t || (t = X()), t.find(".fr-layer.fr-active").removeClass("fr-active").addClass("fr-pactive"), t.find(".fr-image-progress-bar-layer").addClass("fr-active"), t.find(".fr-buttons").hide(), C)) {
                    var a = we();
                    A.popups.setContainer("image.insert", A.$sc);
                    var i = a.offset().left,
                        n = a.offset().top + a.height();
                    A.popups.show("image.insert", i, n, a.outerHeight());
                }
                void 0 === e && k(A.language.translate("Uploading"), 0);
            }
            function z(e) {
                var t = A.popups.get("image.insert");
                if (
                    t &&
                    (t.find(".fr-layer.fr-pactive").addClass("fr-active").removeClass("fr-pactive"), t.find(".fr-image-progress-bar-layer").removeClass("fr-active"), t.find(".fr-buttons").show(), e || A.$el.find("img.fr-error").length)
                ) {
                    if ((A.events.focus(), A.$el.find("img.fr-error").length && (A.$el.find("img.fr-error").remove(), A.undo.saveStep(), A.undo.run(), A.undo.dropRedo()), !A.$wp && C)) {
                        var a = C;
                        fe(!0), A.selection.setAfter(a.get(0)), A.selection.restore();
                    }
                    A.popups.hide("image.insert");
                }
            }
            function k(e, t) {
                var a = A.popups.get("image.insert");
                if (a) {
                    var i = a.find(".fr-image-progress-bar-layer");
                    i.find("h3").text(e + (t ? " ".concat(t, "%") : "")),
                        i.removeClass("fr-error"),
                        t ? (i.find("div").removeClass("fr-indeterminate"), i.find("div > span").css("width", "".concat(t, "%"))) : i.find("div").addClass("fr-indeterminate");
                }
            }
            function B(e) {
                I();
                var t = A.popups.get("image.insert").find(".fr-image-progress-bar-layer");
                t.addClass("fr-error");
                var a = t.find("h3");
                a.text(e), A.events.disableBlur(), a.focus();
            }
            function O(e) {
                pe.call(e.get(0));
            }
            function N() {
                var e = S(this);
                A.popups.hide("image.insert"), e.removeClass("fr-uploading"), e.next().is("br") && e.next().remove(), O(e), A.events.trigger("image.loaded", [e]);
            }
            function L(o, e, s, l, p) {
                l && "string" == typeof l && (l = A.$(l)), A.edit.off(), k(A.language.translate("Loading image")), e && (o = A.helpers.sanitizeURL(o));
                var t = new Image();
                (t.onload = function () {
                    var e, t;
                    if (l) {
                        A.undo.canDo() || l.hasClass("fr-uploading") || A.undo.saveStep();
                        var a = l.data("fr-old-src");
                        l.data("fr-image-pasted") && (a = null),
                            A.$wp ? ((e = l.clone().removeData("fr-old-src").removeClass("fr-uploading").removeAttr("data-fr-image-pasted")).off("load"), a && l.attr("src", a), l.replaceWith(e)) : (e = l);
                        for (var i = e.get(0).attributes, n = 0; n < i.length; n++) {
                            var r = i[n];
                            0 === r.nodeName.indexOf("data-") && e.removeAttr(r.nodeName);
                        }
                        if (void 0 !== s) for (t in s) s.hasOwnProperty(t) && "link" != t && e.attr("data-".concat(t), s[t]);
                        e.on("load", N), e.attr("src", o), A.edit.on(), v(!1), A.undo.saveStep(), A.events.disableBlur(), A.$el.blur(), A.events.trigger(a ? "image.replaced" : "image.inserted", [e, p]);
                    } else (e = Y(o, s, N)), v(!1), A.undo.saveStep(), A.events.disableBlur(), A.$el.blur(), A.events.trigger("image.inserted", [e, p]);
                }),
                    (t.onerror = function () {
                        $(i);
                    }),
                    I(A.language.translate("Loading image")),
                    (t.src = o);
            }
            function _(e, t, a) {
                k(A.language.translate("Loading image"));
                var i = this.status,
                    n = this.response,
                    r = this.responseXML,
                    o = this.responseText;
                try {
                    if (A.opts.imageUploadToS3 || A.opts.imageUploadToAzure)
                        if (201 == i) {
                            var s;
                            if (A.opts.imageUploadToAzure) {
                                if (!1 === A.events.trigger("image.uploadedToAzure", [this.responseURL, a, n], !0)) return A.edit.on(), !1;
                                s = t;
                            } else
                                s = (function p(e) {
                                    try {
                                        var t = S(e).find("Location").text(),
                                            a = S(e).find("Key").text();
                                        return !1 === A.events.trigger("image.uploadedToS3", [t, a, e], !0) ? (A.edit.on(), !1) : t;
                                    } catch (i) {
                                        return $(m, e), !1;
                                    }
                                })(r);
                            s && L(s, !1, [], e, n || r);
                        } else $(m, n || r, e);
                    else if (200 <= i && i < 300) {
                        var l = (function f(e) {
                            try {
                                if (!1 === A.events.trigger("image.uploaded", [e], !0)) return A.edit.on(), !1;
                                var t = JSON.parse(e);
                                return t.link ? t : ($(c, e), !1);
                            } catch (a) {
                                return $(m, e), !1;
                            }
                        })(o);
                        l && L(l.link, !1, l, e, n || o);
                    } else $(d, n || o, e);
                } catch (g) {
                    $(m, n || o, e);
                }
            }
            function M() {
                $(m, this.response || this.responseText || this.responseXML);
            }
            function K(e) {
                if (e.lengthComputable) {
                    var t = ((e.loaded / e.total) * 100) | 0;
                    k(A.language.translate("Uploading"), t);
                }
            }
            function Y(e, t, a) {
                var i,
                    n = S(document.createElement("img")).attr("src", e);
                if (t && void 0 !== t) for (i in t) t.hasOwnProperty(i) && "link" != i && (" data-".concat(i, '="').concat(t[i], '"'), n.attr("data-".concat(i), t[i]));
                var r = A.opts.imageDefaultWidth;
                r && "auto" != r && (r = A.opts.imageResizeWithPercent ? "100%" : "".concat(r, "px")),
                    n.attr("style", r ? "width: ".concat(r, ";") : ""),
                    me(n, A.opts.imageDefaultDisplay, A.opts.imageDefaultAlign),
                    n.on("load", a),
                    n.on("error", a),
                    A.edit.on(),
                    A.events.focus(!0),
                    A.selection.restore(),
                    A.undo.saveStep(),
                    A.opts.imageSplitHTML ? A.markers.split() : A.markers.insert(),
                    A.html.wrap();
                var o = A.$el.find(".fr-marker");
                return o.length ? (o.parent().is("hr") && o.parent().after(o), A.node.isLastSibling(o) && o.parent().hasClass("fr-deletable") && o.insertAfter(o.parent()), o.replaceWith(n)) : A.$el.append(n), A.selection.clear(), n;
            }
            function H() {
                A.edit.on(), z(!0);
            }
            function W(e, t) {
                if (void 0 !== e && 0 < e.length) {
                    if (!1 === A.events.trigger("image.beforeUpload", [e, t])) return !1;
                    var a,
                        i = e[0];
                    if (!((null !== A.opts.imageUploadURL && A.opts.imageUploadURL != E) || A.opts.imageUploadToS3 || A.opts.imageUploadToAzure))
                        return (
                            (function y(n, r) {
                                var o = new FileReader();
                                (o.onload = function () {
                                    var e = o.result;
                                    if (o.result.indexOf("svg+xml") < 0) {
                                        for (var t = atob(o.result.split(",")[1]), a = [], i = 0; i < t.length; i++) a.push(t.charCodeAt(i));
                                        (e = window.URL.createObjectURL(new Blob([new Uint8Array(a)], { type: n.type }))), r && r.data("fr-old-src", r.attr("src")), A.image.insert(e, !1, null, r);
                                    }
                                }),
                                    I(),
                                    o.readAsDataURL(n);
                            })(i, t || C),
                            !1
                        );
                    if ((i.name || (i.name = new Date().getTime() + "." + (i.type || "image/jpeg").replace(/image\//g, "")), i.size > A.opts.imageMaxSize)) return $(R), !1;
                    if (A.opts.imageAllowedTypes.indexOf(i.type.replace(/image\//g, "")) < 0) return $(U), !1;
                    if ((A.drag_support.formdata && (a = A.drag_support.formdata ? new FormData() : null), a)) {
                        var n;
                        if (!1 !== A.opts.imageUploadToS3)
                            for (n in (a.append("key", A.opts.imageUploadToS3.keyStart + new Date().getTime() + "-" + (i.name || "untitled")),
                            a.append("success_action_status", "201"),
                            a.append("X-Requested-With", "xhr"),
                            a.append("Content-Type", i.type),
                            A.opts.imageUploadToS3.params))
                                A.opts.imageUploadToS3.params.hasOwnProperty(n) && a.append(n, A.opts.imageUploadToS3.params[n]);
                        for (n in A.opts.imageUploadParams) A.opts.imageUploadParams.hasOwnProperty(n) && a.append(n, A.opts.imageUploadParams[n]);
                        a.append(A.opts.imageUploadParam, i, i.name);
                        var r,
                            o,
                            s = A.opts.imageUploadURL,
                            l = A.opts.imageUploadMethod;
                        A.opts.imageUploadToS3 && (s = A.opts.imageUploadToS3.uploadURL ? A.opts.imageUploadToS3.uploadURL : "https://".concat(A.opts.imageUploadToS3.region, ".amazonaws.com/").concat(A.opts.imageUploadToS3.bucket)),
                            A.opts.imageUploadToAzure &&
                                ((r = s = A.opts.imageUploadToAzure.uploadURL
                                    ? "".concat(A.opts.imageUploadToAzure.uploadURL, "/").concat(i.name)
                                    : encodeURI("https://".concat(A.opts.imageUploadToAzure.account, ".blob.core.windows.net/").concat(A.opts.imageUploadToAzure.container, "/").concat(i.name))),
                                A.opts.imageUploadToAzure.SASToken && (s += A.opts.imageUploadToAzure.SASToken),
                                (l = "PUT"));
                        var p = A.core.getXHR(s, l);
                        if (A.opts.imageUploadToAzure) {
                            var f = new Date().toUTCString();
                            if (!A.opts.imageUploadToAzure.SASToken && A.opts.imageUploadToAzure.accessKey) {
                                var g = A.opts.imageUploadToAzure.account,
                                    c = A.opts.imageUploadToAzure.container;
                                if (A.opts.imageUploadToAzure.uploadURL) {
                                    var d = A.opts.imageUploadToAzure.uploadURL.split("/");
                                    (c = d.pop()), (g = d.pop().split(".")[0]);
                                }
                                var m = "x-ms-blob-type:BlockBlob\nx-ms-date:".concat(f, "\nx-ms-version:2019-07-07"),
                                    u = encodeURI("/" + g + "/" + c + "/" + i.name),
                                    h = l + "\n\n\n" + i.size + "\n\n" + i.type + "\n\n\n\n\n\n\n" + m + "\n" + u,
                                    v = A.cryptoJSPlugin.cryptoJS.HmacSHA256(h, A.cryptoJSPlugin.cryptoJS.enc.Base64.parse(A.opts.imageUploadToAzure.accessKey)).toString(A.cryptoJSPlugin.cryptoJS.enc.Base64),
                                    b = "SharedKey " + g + ":" + v;
                                (o = v), p.setRequestHeader("Authorization", b);
                            }
                            for (n in (p.setRequestHeader("x-ms-version", "2019-07-07"),
                            p.setRequestHeader("x-ms-date", f),
                            p.setRequestHeader("Content-Type", i.type),
                            p.setRequestHeader("x-ms-blob-type", "BlockBlob"),
                            A.opts.imageUploadParams))
                                A.opts.imageUploadParams.hasOwnProperty(n) && p.setRequestHeader(n, A.opts.imageUploadParams[n]);
                            for (n in A.opts.imageUploadToAzure.params) A.opts.imageUploadToAzure.params.hasOwnProperty(n) && p.setRequestHeader(n, A.opts.imageUploadToAzure.params[n]);
                        }
                        !(function w(t, a, i, n, r, o) {
                            function s() {
                                var e = S(this);
                                e.off("load"),
                                    e.addClass("fr-uploading"),
                                    e.next().is("br") && e.next().remove(),
                                    A.placeholder.refresh(),
                                    O(e),
                                    x(),
                                    I(),
                                    A.edit.off(),
                                    (t.onload = function () {
                                        _.call(t, e, r, o);
                                    }),
                                    (t.onerror = M),
                                    (t.upload.onprogress = K),
                                    (t.onabort = H),
                                    S(e.off("abortUpload")).on("abortUpload", function () {
                                        4 != t.readyState && (t.abort(), n ? (n.attr("src", n.data("fr-old-src")), n.removeClass("fr-uploading")) : e.remove(), fe(!0));
                                    }),
                                    t.send(A.opts.imageUploadToAzure ? i : a);
                            }
                            var l = new FileReader();
                            (l.onload = function () {
                                var e = l.result;
                                if (l.result.indexOf("svg+xml") < 0) {
                                    for (var t = atob(l.result.split(",")[1]), a = [], i = 0; i < t.length; i++) a.push(t.charCodeAt(i));
                                    e = window.URL.createObjectURL(new Blob([new Uint8Array(a)], { type: "image/jpeg" }));
                                }
                                n
                                    ? (n.on("load", s),
                                      n.on("error", function () {
                                          s(), S(this).off("error");
                                      }),
                                      A.edit.on(),
                                      A.undo.saveStep(),
                                      n.data("fr-old-src", n.attr("src")),
                                      n.attr("src", e))
                                    : Y(e, null, s);
                            }),
                                l.readAsDataURL(i);
                        })(p, a, i, t || C, r, o);
                    }
                }
            }
            function G(e) {
                if (e.is("img") && 0 < e.parents(".fr-img-caption").length) return e.parents(".fr-img-caption");
            }
            function V(e) {
                var t = e.originalEvent.dataTransfer;
                if (t && t.files && t.files.length) {
                    var a = t.files[0];
                    if (a && a.type && -1 !== a.type.indexOf("image") && 0 <= A.opts.imageAllowedTypes.indexOf(a.type.replace(/image\//g, ""))) {
                        if (!A.opts.imageUpload) return e.preventDefault(), e.stopPropagation(), !1;
                        A.markers.remove(), A.markers.insertAtPoint(e.originalEvent), A.$el.find(".fr-marker").replaceWith(Le.MARKERS), 0 === A.$el.find(".fr-marker").length && A.selection.setAtEnd(A.el), A.popups.hideAll();
                        var i = A.popups.get("image.insert");
                        i || (i = X()), A.popups.setContainer("image.insert", A.$sc);
                        var n = e.originalEvent.pageX,
                            r = e.originalEvent.pageY;
                        if (A.opts.iframe) {
                            var o = A.helpers.getPX(A.$wp.find(".fr-iframe").css("padding-top")),
                                s = A.helpers.getPX(A.$wp.find(".fr-iframe").css("padding-left"));
                            (r += A.$iframe.offset().top + o), (n += A.$iframe.offset().left + s);
                        }
                        return A.popups.show("image.insert", n, r), I(), 0 <= A.opts.imageAllowedTypes.indexOf(a.type.replace(/image\//g, "")) ? (fe(!0), W(t.files)) : $(U), e.preventDefault(), e.stopPropagation(), !1;
                    }
                }
            }
            function X(e) {
                if (e) return A.popups.onRefresh("image.insert", g), A.popups.onHide("image.insert", u), !0;
                var t,
                    a,
                    i = "";
                A.opts.imageUpload || -1 === A.opts.imageInsertButtons.indexOf("imageUpload") || A.opts.imageInsertButtons.splice(A.opts.imageInsertButtons.indexOf("imageUpload"), 1);
                var n = A.button.buildList(A.opts.imageInsertButtons);
                "" !== n && (i = '<div class="fr-buttons fr-tabs">'.concat(n, "</div>"));
                var r = A.opts.imageInsertButtons.indexOf("imageUpload"),
                    o = A.opts.imageInsertButtons.indexOf("imageByURL"),
                    s = "";
                0 <= r &&
                    ((t = " fr-active"),
                    0 <= o && o < r && (t = ""),
                    (s = '<div class="fr-image-upload-layer'
                        .concat(t, ' fr-layer" id="fr-image-upload-layer-')
                        .concat(A.id, '"><strong>')
                        .concat(A.language.translate("Drop image"), "</strong><br>(")
                        .concat(A.language.translate("or click"), ')<div class="fr-form"><input type="file" id="fr_upload_file_btn" accept="image/')
                        .concat(A.opts.imageAllowedTypes.join(", image/").toLowerCase(), '" tabIndex="-1" aria-labelledby="fr-image-upload-layer-')
                        .concat(A.id, '" role="button"></div></div>')));
                var l = "";
                0 <= o &&
                    ((t = " fr-active"),
                    0 <= r && r < o && (t = ""),
                    (l = '<div class="fr-image-by-url-layer'
                        .concat(t, ' fr-layer" id="fr-image-by-url-layer-')
                        .concat(A.id, '"><div class="fr-input-line"><input id="fr-image-by-url-layer-text-')
                        .concat(
                            A.id,
                            '" type="text" placeholder="http://" tabIndex="1" aria-required="true"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageInsertByURL" tabIndex="2" role="button">'
                        )
                        .concat(A.language.translate("Insert"), "</button></div></div>")));
                var p = {
                    buttons: i,
                    upload_layer: s,
                    by_url_layer: l,
                    progress_bar:
                        '<div class="fr-image-progress-bar-layer fr-layer"><h3 tabIndex="-1" class="fr-message">Uploading</h3><div class="fr-loader"><span class="fr-progress"></span></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-dismiss" data-cmd="imageDismissError" tabIndex="2" role="button">OK</button></div></div>',
                };
                return (
                    1 <= A.opts.imageInsertButtons.length && (a = A.popups.create("image.insert", p)),
                    A.$wp &&
                        A.events.$on(A.$wp, "scroll", function () {
                            C && A.popups.isVisible("image.insert") && ve();
                        }),
                    (function f(i) {
                        A.events.$on(
                            i,
                            "dragover dragenter",
                            ".fr-image-upload-layer",
                            function (e) {
                                return S(this).addClass("fr-drop"), (A.browser.msie || A.browser.edge) && e.preventDefault(), !1;
                            },
                            !0
                        ),
                            A.events.$on(
                                i,
                                "dragleave dragend",
                                ".fr-image-upload-layer",
                                function (e) {
                                    return S(this).removeClass("fr-drop"), (A.browser.msie || A.browser.edge) && e.preventDefault(), !1;
                                },
                                !0
                            ),
                            A.events.$on(
                                i,
                                "drop",
                                ".fr-image-upload-layer",
                                function (e) {
                                    e.preventDefault(), e.stopPropagation(), S(this).removeClass("fr-drop");
                                    var t = e.originalEvent.dataTransfer;
                                    if (t && t.files) {
                                        var a = i.data("instance") || A;
                                        a.events.disableBlur(), a.image.upload(t.files), a.events.enableBlur();
                                    }
                                },
                                !0
                            ),
                            A.helpers.isIOS() &&
                                A.events.$on(
                                    i,
                                    "touchstart",
                                    '.fr-image-upload-layer input[type="file"]',
                                    function () {
                                        S(this).trigger("click");
                                    },
                                    !0
                                ),
                            A.events.$on(
                                i,
                                "change",
                                '.fr-image-upload-layer input[type="file"]',
                                function () {
                                    if (this.files) {
                                        var e = i.data("instance") || A;
                                        e.events.disableBlur(), i.find("input:focus").blur(), e.events.enableBlur(), e.image.upload(this.files, C);
                                                                               
                                    }
                                    // S(this).val("");
                                },
                                !0
                            );
                    })(a),
                    a
                );
            }
            function F() {
                C &&
                    A.popups
                        .get("image.alt")
                        .find("input")
                        .val(C.attr("alt") || "")
                        .trigger("change");
            }
            function q() {
                var e = A.popups.get("image.alt");
                e || (e = j()), z(), A.popups.refresh("image.alt"), A.popups.setContainer("image.alt", A.$sc);
                var t = we();
                Ae() && (t = t.find(".fr-img-wrap"));
                var a = t.offset().left + t.outerWidth() / 2,
                    i = t.offset().top + t.outerHeight();
                A.popups.show("image.alt", a, i, t.outerHeight(), !0);
            }
            function j(e) {
                if (e) return A.popups.onRefresh("image.alt", F), !0;
                var t = {
                        buttons: '<div class="fr-buttons fr-tabs">'.concat(A.button.buildList(A.opts.imageAltButtons), "</div>"),
                        alt_layer: '<div class="fr-image-alt-layer fr-layer fr-active" id="fr-image-alt-layer-'
                            .concat(A.id, '"><div class="fr-input-line"><input id="fr-image-alt-layer-text-')
                            .concat(A.id, '" type="text" placeholder="')
                            .concat(A.language.translate("Alternative Text"), '" tabIndex="1"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageSetAlt" tabIndex="2" role="button">')
                            .concat(A.language.translate("Update"), "</button></div></div>"),
                    },
                    a = A.popups.create("image.alt", t);
                return (
                    A.$wp &&
                        A.events.$on(A.$wp, "scroll.image-alt", function () {
                            C && A.popups.isVisible("image.alt") && q();
                        }),
                    a
                );
            }
            function J() {
                var e = A.popups.get("image.size");
                if (C)
                    if (Ae()) {
                        var t = C.parent();
                        t.get(0).style.width || (t = C.parent().parent()), e.find('input[name="width"]').val(t.get(0).style.width).trigger("change"), e.find('input[name="height"]').val(t.get(0).style.height).trigger("change");
                    } else e.find('input[name="width"]').val(C.get(0).style.width).trigger("change"), e.find('input[name="height"]').val(C.get(0).style.height).trigger("change");
            }
            function Q() {
                var e = A.popups.get("image.size");
                e || (e = Z()), z(), A.popups.refresh("image.size"), A.popups.setContainer("image.size", A.$sc);
                var t = we();
                Ae() && (t = t.find(".fr-img-wrap"));
                var a = t.offset().left + t.outerWidth() / 2,
                    i = t.offset().top + t.outerHeight();
                A.popups.show("image.size", a, i, t.outerHeight(), !0);
            }
            function Z(e) {
                if (e) return A.popups.onRefresh("image.size", J), !0;
                var t = {
                        buttons: '<div class="fr-buttons fr-tabs">'.concat(A.button.buildList(A.opts.imageSizeButtons), "</div>"),
                        size_layer: '<div class="fr-image-size-layer fr-layer fr-active" id="fr-image-size-layer-'
                            .concat(A.id, '"><div class="fr-image-group"><div class="fr-input-line"><input id="fr-image-size-layer-width-\'')
                            .concat(A.id, '" type="text" name="width" placeholder="')
                            .concat(A.language.translate("Width"), '" tabIndex="1"></div><div class="fr-input-line"><input id="fr-image-size-layer-height')
                            .concat(A.id, '" type="text" name="height" placeholder="')
                            .concat(A.language.translate("Height"), '" tabIndex="1"></div></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageSetSize" tabIndex="2" role="button">')
                            .concat(A.language.translate("Update"), "</button></div></div>"),
                    },
                    a = A.popups.create("image.size", t);
                return (
                    A.$wp &&
                        A.events.$on(A.$wp, "scroll.image-size", function () {
                            C && A.popups.isVisible("image.size") && Q();
                        }),
                    a
                );
            }
            function ee(e, t, a, i) {
                return (e.pageX = t), w.call(this, e), (e.pageX = e.pageX + a * Math.floor(Math.pow(1.1, i))), D.call(this, e), T.call(this, e), ++i;
            }
            function te(e) {
                (e = e || we()) &&
                    !1 !== A.events.trigger("image.beforeRemove", [e]) &&
                    (A.popups.hideAll(),
                    be(),
                    fe(!0),
                    A.undo.canDo() || A.undo.saveStep(),
                    e.get(0) == A.el
                        ? e.removeAttr("src")
                        : (e.get(0).parentNode && "A" == e.get(0).parentNode.tagName
                              ? (A.selection.setBefore(e.get(0).parentNode) || A.selection.setAfter(e.get(0).parentNode) || e.parent().after(Le.MARKERS), S(e.get(0).parentNode).remove())
                              : (A.selection.setBefore(e.get(0)) || A.selection.setAfter(e.get(0)) || e.after(Le.MARKERS), e.remove()),
                          A.html.fillEmptyBlocks(),
                          A.selection.restore()),
                    A.undo.saveStep());
            }
            function ae(e) {
                var t = e.which;
                if (C && (t == Le.KEYCODE.BACKSPACE || t == Le.KEYCODE.DELETE)) return e.preventDefault(), e.stopPropagation(), te(), !1;
                if (C && t == Le.KEYCODE.ESC) {
                    var a = C;
                    return fe(!0), A.selection.setAfter(a.get(0)), A.selection.restore(), e.preventDefault(), !1;
                }
                if (!C || (t != Le.KEYCODE.ARROW_LEFT && t != Le.KEYCODE.ARROW_RIGHT))
                    return C && t === Le.KEYCODE.TAB ? (e.preventDefault(), e.stopPropagation(), fe(!0), !1) : C && t != Le.KEYCODE.F10 && !A.keys.isBrowserAction(e) ? (e.preventDefault(), e.stopPropagation(), !1) : void 0;
                var i = C.get(0);
                return fe(!0), t == Le.KEYCODE.ARROW_LEFT ? A.selection.setBefore(i) : A.selection.setAfter(i), A.selection.restore(), e.preventDefault(), !1;
            }
            function ie(e) {
                if (e && "IMG" == e.tagName) {
                    if (
                        (A.node.hasClass(e, "fr-uploading") || A.node.hasClass(e, "fr-error") ? e.parentNode.removeChild(e) : A.node.hasClass(e, "fr-draggable") && e.classList.remove("fr-draggable"),
                        e.parentNode && e.parentNode.parentNode && A.node.hasClass(e.parentNode.parentNode, "fr-img-caption"))
                    ) {
                        var t = e.parentNode.parentNode;
                        t.removeAttribute("contenteditable"), t.removeAttribute("draggable"), t.classList.remove("fr-draggable");
                        var a = e.nextSibling;
                        a && a.removeAttribute("contenteditable");
                    }
                } else if (e && e.nodeType == Node.ELEMENT_NODE) for (var i = e.querySelectorAll("img.fr-uploading, img.fr-error, img.fr-draggable"), n = 0; n < i.length; n++) ie(i[n]);
            }
            function ne(e) {
                if (!1 === A.events.trigger("image.beforePasteUpload", [e])) return !1;
                (C = S(e)),
                    x(),
                    r(),
                    ve(),
                    I(),
                    C.on("load", function () {
                        var t = [];
                        x(),
                            S(A.popups.get("image.insert").get(0)).find("div.fr-active.fr-error").length < 1 && I(),
                            S(this)
                                .data("events")
                                .find(function (e) {
                                    "load" === e[0] && t.push(e);
                                }),
                            t.length <= 1 && S(this).off("load");
                    });
                for (var t = S(e).attr("src").split(","), a = atob(t[1]), i = [], n = 0; n < a.length; n++) i.push(a.charCodeAt(n));
                W([new Blob([new Uint8Array(i)], { type: t[0].replace(/data\:/g, "").replace(/;base64/g, "") })], C);
            }
            function re() {
                A.opts.imagePaste
                    ? A.$el.find("img[data-fr-image-pasted]").each(function (e, i) {
                          if (A.opts.imagePasteProcess) {
                              var t = A.opts.imageDefaultWidth;
                              t && "auto" != t && (t += A.opts.imageResizeWithPercent ? "%" : "px"), S(i).css("width", t).removeClass("fr-dii fr-dib fr-fir fr-fil"), me(S(i), A.opts.imageDefaultDisplay, A.opts.imageDefaultAlign);
                          }
                          if (0 === i.src.indexOf("data:")) ne(i);
                          else if (0 === i.src.indexOf("blob:") || (0 === i.src.indexOf("http") && A.opts.imageUploadRemoteUrls && A.opts.imageCORSProxy)) {
                              var a = new Image();
                              (a.crossOrigin = "Anonymous"),
                                  (a.onload = function () {
                                      var e,
                                          t = A.o_doc.createElement("CANVAS"),
                                          a = t.getContext("2d");
                                      (t.height = this.naturalHeight),
                                          (t.width = this.naturalWidth),
                                          a.drawImage(this, 0, 0),
                                          setTimeout(function () {
                                              ne(i);
                                          }, 0),
                                          (e = 2e3 < this.naturalWidth || 1500 < this.naturalHeight ? "jpeg" : "png"),
                                          (i.src = t.toDataURL("image/".concat(e)));
                                  }),
                                  (a.src = (0 === i.src.indexOf("blob:") ? "" : "".concat(A.opts.imageCORSProxy, "/")) + i.src);
                          } else 0 !== i.src.indexOf("http") || 0 === i.src.indexOf("https://mail.google.com/mail") ? (A.selection.save(), S(i).remove(), A.selection.restore()) : S(i).removeAttr("data-fr-image-pasted");
                      })
                    : A.$el.find("img[data-fr-image-pasted]").remove();
            }
            function oe(e) {
                var t = e.target.result,
                    a = A.opts.imageDefaultWidth;
                a && "auto" != a && (a += A.opts.imageResizeWithPercent ? "%" : "px"), A.undo.saveStep(), A.html.insert('<img data-fr-image-pasted="true" src="'.concat(t, '"').concat(a ? ' style="width: '.concat(a, ';"') : "", ">"));
                var i = A.$el.find('img[data-fr-image-pasted="true"]');
                i && me(i, A.opts.imageDefaultDisplay, A.opts.imageDefaultAlign), A.events.trigger("paste.after");
            }
            function se(e) {
                if (e && e.clipboardData && e.clipboardData.items) {
                    var t = null;
                    if ((e.clipboardData.types && -1 != [].indexOf.call(e.clipboardData.types, "text/rtf")) || e.clipboardData.getData("text/rtf")) t = e.clipboardData.items[0].getAsFile();
                    else for (var a = 0; a < e.clipboardData.items.length && !(t = e.clipboardData.items[a].getAsFile()); a++);
                    if (t)
                        return (
                            (function i(e) {
                                var t = new FileReader();
                                (t.onload = oe), t.readAsDataURL(e);
                            })(t),
                            !1
                        );
                }
            }
            function le(e) {
                return (e = e.replace(/<img /gi, '<img data-fr-image-pasted="true" '));
            }
            function pe(e) {
                if ("false" == S(this).parents("[contenteditable]").not(".fr-element").not(".fr-img-caption").not("body").first().attr("contenteditable")) return !0;
                if (e && "touchend" == e.type && a) return !0;
                if (e && A.edit.isDisabled()) return e.stopPropagation(), e.preventDefault(), !1;
                for (var t = 0; t < Le.INSTANCES.length; t++) Le.INSTANCES[t] != A && Le.INSTANCES[t].events.trigger("image.hideResizer");
                A.toolbar.disable(),
                    e && (e.stopPropagation(), e.preventDefault()),
                    A.helpers.isMobile() && (A.events.disableBlur(), A.$el.blur(), A.events.enableBlur()),
                    A.opts.iframe && A.size.syncIframe(),
                    (C = S(this)),
                    be(),
                    x(),
                    r(),
                    A.browser.msie ? (A.popups.areVisible() && A.events.disableBlur(), A.win.getSelection && (A.win.getSelection().removeAllRanges(), A.win.getSelection().addRange(A.doc.createRange()))) : A.selection.clear(),
                    A.helpers.isIOS() && (A.events.disableBlur(), A.$el.blur()),
                    A.button.bulkRefresh(),
                    A.events.trigger("video.hideResizer");
            }
            function fe(e) {
                C &&
                    ((function t() {
                        return ge;
                    })() ||
                        !0 === e) &&
                    (A.toolbar.enable(), l.removeClass("fr-active"), A.popups.hideAll(), (C = null), de(), (p = null), f && f.hide());
            }
            (n[i] = "Image cannot be loaded from the passed link."),
                (n[c] = "No link in upload response."),
                (n[d] = "Error during file upload."),
                (n[m] = "Parsing response failed."),
                (n[R] = "File is too large."),
                (n[U] = "Image file type is invalid."),
                (n[7] = "Files can be uploaded only to same domain in IE 8 and IE 9.");
            var ge = !(n[8] = "Image file is corrupted.");
            function ce() {
                ge = !0;
            }
            function de() {
                ge = !1;
            }
            function me(e, t, a) {
                !A.opts.htmlUntouched && A.opts.useClasses
                    ? (e.removeClass("fr-fil fr-fir fr-dib fr-dii"), a && e.addClass("fr-fi".concat(a[0])), t && e.addClass("fr-di".concat(t[0])))
                    : "inline" == t
                    ? (e.css({ display: "inline-block", verticalAlign: "bottom", margin: A.opts.imageDefaultMargin }),
                      "center" == a
                          ? e.css({ float: "none", marginBottom: "", marginTop: "", maxWidth: "calc(100% - ".concat(2 * A.opts.imageDefaultMargin, "px)"), textAlign: "center" })
                          : "left" == a
                          ? e.css({ float: "left", marginLeft: 0, maxWidth: "calc(100% - ".concat(A.opts.imageDefaultMargin, "px)"), textAlign: "left" })
                          : e.css({ float: "right", marginRight: 0, maxWidth: "calc(100% - ".concat(A.opts.imageDefaultMargin, "px)"), textAlign: "right" }))
                    : "block" == t &&
                      (e.css({ display: "block", float: "none", verticalAlign: "top", margin: "".concat(A.opts.imageDefaultMargin, "px auto"), textAlign: "center" }),
                      "left" == a ? e.css({ marginLeft: 0, textAlign: "left" }) : "right" == a && e.css({ marginRight: 0, textAlign: "right" }));
            }
            function ue(e) {
                if ((void 0 === e && (e = we()), e)) {
                    if (e.hasClass("fr-fil")) return "left";
                    if (e.hasClass("fr-fir")) return "right";
                    if (e.hasClass("fr-dib") || e.hasClass("fr-dii")) return "center";
                    var t = e.css("float");
                    if ((e.css("float", "none"), "block" == e.css("display"))) {
                        if ((e.css("float", ""), e.css("float") != t && e.css("float", t), 0 === parseInt(e.css("margin-left"), 10))) return "left";
                        if (0 === parseInt(e.css("margin-right"), 10)) return "right";
                    } else {
                        if ((e.css("float", ""), e.css("float") != t && e.css("float", t), "left" == e.css("float"))) return "left";
                        if ("right" == e.css("float")) return "right";
                    }
                }
                return "center";
            }
            function he(e) {
                void 0 === e && (e = we());
                var t = e.css("float");
                return e.css("float", "none"), "block" == e.css("display") ? (e.css("float", ""), e.css("float") != t && e.css("float", t), "block") : (e.css("float", ""), e.css("float") != t && e.css("float", t), "inline");
            }
            function ve() {
                var e = A.popups.get("image.insert");
                e || (e = X()), A.popups.isVisible("image.insert") || (z(), A.popups.refresh("image.insert"), A.popups.setContainer("image.insert", A.$sc));
                var t = we();
                Ae() && (t = t.find(".fr-img-wrap"));
                var a = t.offset().left + t.outerWidth() / 2,
                    i = t.offset().top + t.outerHeight();
                A.popups.show("image.insert", a, i, t.outerHeight(!0), !0);
            }
            function be() {
                if (C) {
                    A.events.disableBlur(), A.selection.clear();
                    var e = A.doc.createRange();
                    e.selectNode(C.get(0)), A.browser.msie && e.collapse(!0), A.selection.get().addRange(e), A.events.enableBlur();
                }
            }
            function ye() {
                return C;
            }
            function we() {
                return Ae() ? C.parents(".fr-img-caption").first() : C;
            }
            function Ae() {
                return !!C && 0 < C.parents(".fr-img-caption").length;
            }
            function Ce(e) {
                for (var t = document.createDocumentFragment(); e.firstChild; ) {
                    var a = e.removeChild(e.firstChild);
                    t.appendChild(a);
                }
                e.parentNode.replaceChild(t, e);
            }
            return {
                _init: function Se() {
                    var i;
                    (function e() {
                        A.events.$on(A.$el, A._mousedown, "IMG" == A.el.tagName ? null : 'img:not([contenteditable="false"])', function (e) {
                            if ("false" == S(this).parents("contenteditable").not(".fr-element").not(".fr-img-caption").not("body").first().attr("contenteditable")) return !0;
                            A.helpers.isMobile() || A.selection.clear(),
                                (t = !0),
                                A.popups.areVisible() && A.events.disableBlur(),
                                A.browser.msie && (A.events.disableBlur(), A.$el.attr("contenteditable", !1)),
                                A.draggable || "touchstart" == e.type || e.preventDefault(),
                                e.stopPropagation();
                        }),
                            A.events.$on(A.$el, A._mousedown, ".fr-img-caption .fr-inner", function (e) {
                                A.core.hasFocus() || A.events.focus(), e.stopPropagation();
                            }),
                            A.events.$on(A.$el, "paste", ".fr-img-caption .fr-inner", function (e) {
                                A.toolbar.hide(), e.stopPropagation();
                            }),
                            A.events.$on(A.$el, A._mouseup, "IMG" == A.el.tagName ? null : 'img:not([contenteditable="false"])', function (e) {
                                if ("false" == S(this).parents("contenteditable").not(".fr-element").not(".fr-img-caption").not("body").first().attr("contenteditable")) return !0;
                                t && ((t = !1), e.stopPropagation(), A.browser.msie && (A.$el.attr("contenteditable", !0), A.events.enableBlur()));
                            }),
                            A.events.on(
                                "keyup",
                                function (e) {
                                    if (e.shiftKey && "" === A.selection.text().replace(/\n/g, "") && A.keys.isArrow(e.which)) {
                                        var t = A.selection.element(),
                                            a = A.selection.endElement();
                                        t && "IMG" == t.tagName ? O(S(t)) : a && "IMG" == a.tagName && O(S(a));
                                    }
                                },
                                !0
                            ),
                            A.events.on("drop", V),
                            A.events.on("element.beforeDrop", G),
                            A.events.on("mousedown window.mousedown", ce),
                            A.events.on("window.touchmove", de),
                            A.events.on("mouseup window.mouseup", function () {
                                if (C) return fe(), !1;
                                de();
                            }),
                            A.events.on("commands.mousedown", function (e) {
                                0 < e.parents(".fr-toolbar").length && fe();
                            }),
                            A.events.on("image.resizeEnd", function () {
                                A.opts.iframe && A.size.syncIframe();
                            }),
                            A.events.on("blur image.hideResizer commands.undo commands.redo element.dropped", function () {
                                fe(!(t = !1));
                            }),
                            A.events.on("modals.hide", function () {
                                C && (be(), A.selection.clear());
                            }),
                            A.events.on("image.resizeEnd", function () {
                                A.win.getSelection && O(C);
                            }),
                            A.opts.imageAddNewLine &&
                                A.events.on("image.inserted", function (e) {
                                    var t = e.get(0);
                                    for (t.nextSibling && "BR" === t.nextSibling.tagName && (t = t.nextSibling); t && !A.node.isElement(t); ) t = A.node.isLastSibling(t) ? t.parentNode : null;
                                    A.node.isElement(t) && (A.opts.enter === Le.ENTER_BR ? e.after("<br>") : S(A.node.blockParent(e.get(0))).after("<".concat(A.html.defaultTag(), "><br></").concat(A.html.defaultTag(), ">")));
                                });
                    })(),
                        "IMG" == A.el.tagName && A.$el.addClass("fr-view"),
                        A.events.$on(A.$el, A.helpers.isMobile() && !A.helpers.isWindowsPhone() ? "touchend" : "click", "IMG" == A.el.tagName ? null : 'img:not([contenteditable="false"])', pe),
                        A.helpers.isMobile() &&
                            (A.events.$on(A.$el, "touchstart", "IMG" == A.el.tagName ? null : 'img:not([contenteditable="false"])', function () {
                                a = !1;
                            }),
                            A.events.$on(A.$el, "touchmove", function () {
                                a = !0;
                            })),
                        A.$wp
                            ? (A.events.on("window.keydown keydown", ae, !0),
                              A.events.on(
                                  "keyup",
                                  function (e) {
                                      if (C && e.which == Le.KEYCODE.ENTER) return !1;
                                  },
                                  !0
                              ),
                              A.events.$on(A.$el, "keydown", function () {
                                  var e = A.selection.element();
                                  (e.nodeType === Node.TEXT_NODE || ("BR" == e.tagName && A.node.isLastSibling(e))) && (e = e.parentNode),
                                      A.node.hasClass(e, "fr-inner") ||
                                          (A.node.hasClass(e, "fr-img-caption") || (e = S(e).parents(".fr-img-caption").get(0)), A.node.hasClass(e, "fr-img-caption") && (S(e).after(Le.INVISIBLE_SPACE + Le.MARKERS), A.selection.restore()));
                              }))
                            : A.events.$on(A.$win, "keydown", ae),
                        A.events.on(
                            "toolbar.esc",
                            function () {
                                if (C) {
                                    if (A.$wp) A.events.disableBlur(), A.events.focus();
                                    else {
                                        var e = C;
                                        fe(!0), A.selection.setAfter(e.get(0)), A.selection.restore();
                                    }
                                    return !1;
                                }
                            },
                            !0
                        ),
                        A.events.on(
                            "toolbar.focusEditor",
                            function () {
                                if (C) return !1;
                            },
                            !0
                        ),
                        A.events.on(
                            "window.cut window.copy",
                            function (e) {
                                if (C && A.popups.isVisible("image.edit") && !A.popups.get("image.edit").find(":focus").length) {
                                    var t = we();
                                    Ae() ? (t.before(Le.START_MARKER), t.after(Le.END_MARKER), A.selection.restore(), A.paste.saveCopiedText(t.get(0).outerHTML, t.text())) : (be(), A.paste.saveCopiedText(C.get(0).outerHTML, C.attr("alt"))),
                                        "copy" == e.type
                                            ? setTimeout(function () {
                                                  O(C);
                                              })
                                            : (fe(!0),
                                              A.undo.saveStep(),
                                              setTimeout(function () {
                                                  A.undo.saveStep();
                                              }, 0));
                                }
                            },
                            !0
                        ),
                        A.browser.msie &&
                            A.events.on("keydown", function (e) {
                                if (!A.selection.isCollapsed() || !C) return !0;
                                var t = e.which;
                                t == Le.KEYCODE.C && A.keys.ctrlKey(e) ? A.events.trigger("window.copy") : t == Le.KEYCODE.X && A.keys.ctrlKey(e) && A.events.trigger("window.cut");
                            }),
                        A.events.$on(S(A.o_win), "keydown", function (e) {
                            var t = e.which;
                            if (C && t == Le.KEYCODE.BACKSPACE) return e.preventDefault(), !1;
                        }),
                        A.events.$on(A.$win, "keydown", function (e) {
                            var t = e.which;
                            C && C.hasClass("fr-uploading") && t == Le.KEYCODE.ESC && C.trigger("abortUpload");
                        }),
                        A.events.on("destroy", function () {
                            C && C.hasClass("fr-uploading") && C.trigger("abortUpload");
                        }),
                        A.events.on("paste.before", se),
                        A.events.on("paste.beforeCleanup", le),
                        A.events.on("paste.after", re),
                        A.events.on("html.set", h),
                        A.events.on("html.inserted", h),
                        h(),
                        A.events.on("destroy", function () {
                            s = [];
                        }),
                        A.events.on("html.processGet", ie),
                        A.opts.imageOutputSize &&
                            A.events.on("html.beforeGet", function () {
                                i = A.el.querySelectorAll("img");
                                for (var e = 0; e < i.length; e++) {
                                    var t = i[e].style.width || S(i[e]).width(),
                                        a = i[e].style.height || S(i[e]).height();
                                    t && i[e].setAttribute("width", "".concat(t).replace(/px/, "")), a && i[e].setAttribute("height", "".concat(a).replace(/px/, ""));
                                }
                            }),
                        A.opts.iframe && A.events.on("image.loaded", A.size.syncIframe),
                        A.$wp && (v(), A.events.on("contentChanged", v)),
                        A.events.$on(S(A.o_win), "orientationchange.image", function () {
                            setTimeout(function () {
                                C && O(C);
                            }, 100);
                        }),
                        P(!0),
                        X(!0),
                        Z(!0),
                        j(!0),
                        A.events.on("node.remove", function (e) {
                            if ("IMG" == e.get(0).tagName) return te(e), !1;
                        });
                },
                showInsertPopup: function Ee() {
                    var e = A.$tb.find('.fr-command[data-cmd="insertImage"]'),
                        t = A.popups.get("image.insert");
                    if ((t || (t = X()), z(), !t.hasClass("fr-active")))
                        if ((A.popups.refresh("image.insert"), A.popups.setContainer("image.insert", A.$tb), e.isVisible())) {
                            var a = A.button.getPosition(e),
                                i = a.left,
                                n = a.top;
                            A.popups.show("image.insert", i, n, e.outerHeight());
                        } else A.position.forSelection(t), A.popups.show("image.insert");
                },
                showLayer: function Re(e) {
                    var t,
                        a,
                        i = A.popups.get("image.insert");
                    if (C || A.opts.toolbarInline) {
                        if (C) {
                            var n = we();
                            Ae() && (n = n.find(".fr-img-wrap")), (a = n.offset().top + n.outerHeight()), (t = n.offset().left);
                        }
                    } else {
                        var r = A.$tb.find('.fr-command[data-cmd="insertImage"]');
                        (t = r.offset().left), (a = r.offset().top + (A.opts.toolbarBottom ? 10 : r.outerHeight() - 10));
                    }
                    !C && A.opts.toolbarInline && ((a = i.offset().top - A.helpers.getPX(i.css("margin-top"))), i.hasClass("fr-above") && (a += i.outerHeight())),
                        i.find(".fr-layer").removeClass("fr-active"),
                        i.find(".fr-".concat(e, "-layer")).addClass("fr-active"),
                        A.popups.show("image.insert", t, a, C ? C.outerHeight() : 0),
                        A.accessibility.focusPopup(i);
                },
                refreshUploadButton: function Ue(e) {
                    var t = A.popups.get("image.insert");
                    t && t.find(".fr-image-upload-layer").hasClass("fr-active") && e.addClass("fr-active").attr("aria-pressed", !0);
                },
                refreshByURLButton: function xe(e) {
                    var t = A.popups.get("image.insert");
                    t && t.find(".fr-image-by-url-layer").hasClass("fr-active") && e.addClass("fr-active").attr("aria-pressed", !0);
                },
                upload: W,
                insertByURL: function De() {
                    var e = A.popups.get("image.insert").find(".fr-image-by-url-layer input");
                    if (0 < e.val().length) {
                        I(), k(A.language.translate("Loading image"));
                        var t = e.val().trim();
                        if (A.opts.imageUploadRemoteUrls && A.opts.imageCORSProxy && A.opts.imageUpload) {
                            var a = new XMLHttpRequest();
                            (a.onload = function () {
                                200 == this.status ? W([new Blob([this.response], { type: this.response.type || "image/png" })], C) : $(i);
                            }),
                                (a.onerror = function () {
                                    L(t, !0, [], C);
                                }),
                                a.open("GET", "".concat(A.opts.imageCORSProxy, "/").concat(t), !0),
                                (a.responseType = "blob"),
                                a.send();
                        } else L(t, !0, [], C);
                        e.val(""), e.blur();
                    }
                },
                align: function Te(e) {
                    var t = we();
                    t.removeClass("fr-fir fr-fil"), !A.opts.htmlUntouched && A.opts.useClasses ? ("left" == e ? t.addClass("fr-fil") : "right" == e && t.addClass("fr-fir")) : me(t, he(), e), be(), x(), r(), A.selection.clear();
                },
                refreshAlign: function $e(e) {
                    C &&
                        e
                            .find("> *")
                            .first()
                            .replaceWith(A.icon.create("image-align-".concat(ue())));
                },
                refreshAlignOnShow: function Pe(e, t) {
                    C && t.find('.fr-command[data-param1="'.concat(ue(), '"]')).addClass("fr-active").attr("aria-selected", !0);
                },
                display: function Ie(e) {
                    var t = we();
                    t.removeClass("fr-dii fr-dib"), !A.opts.htmlUntouched && A.opts.useClasses ? ("inline" == e ? t.addClass("fr-dii") : "block" == e && t.addClass("fr-dib")) : me(t, e, ue()), be(), x(), r(), A.selection.clear();
                },
                refreshDisplayOnShow: function ze(e, t) {
                    C && t.find('.fr-command[data-param1="'.concat(he(), '"]')).addClass("fr-active").attr("aria-selected", !0);
                },
                replace: ve,
                back: function e() {
                    C ? (A.events.disableBlur(), S(".fr-popup input:focus").blur(), O(C)) : (A.events.disableBlur(), A.selection.restore(), A.events.enableBlur(), A.popups.hide("image.insert"), A.toolbar.showInline());
                },
                get: ye,
                getEl: we,
                insert: L,
                showProgressBar: I,
                remove: te,
                hideProgressBar: z,
                applyStyle: function ke(e, t, a) {
                    if ((void 0 === t && (t = A.opts.imageStyles), void 0 === a && (a = A.opts.imageMultipleStyles), !C)) return !1;
                    var i = we();
                    if (!a) {
                        var n = Object.keys(t);
                        n.splice(n.indexOf(e), 1), i.removeClass(n.join(" "));
                    }
                    "object" == _e(t[e]) ? (i.removeAttr("style"), i.css(t[e].style)) : i.toggleClass(e), O(C);
                },
                showAltPopup: q,
                showSizePopup: Q,
                setAlt: function Be(e) {
                    if (C) {
                        var t = A.popups.get("image.alt");
                        C.attr("alt", e || t.find("input").val() || ""), t.find("input:focus").blur(), O(C);
                    }
                },
                setSize: function Oe(e, t) {
                    if (C) {
                        var a = A.popups.get("image.size");
                        (e = e || a.find('input[name="width"]').val() || ""), (t = t || a.find('input[name="height"]').val() || "");
                        var i = /^[\d]+((px)|%)*$/g;
                        C.removeAttr("width").removeAttr("height"),
                            e.match(i) ? C.css("width", e) : C.css("width", ""),
                            t.match(i) ? C.css("height", t) : C.css("height", ""),
                            Ae() &&
                                (C.parents(".fr-img-caption").removeAttr("width").removeAttr("height"),
                                e.match(i) ? C.parents(".fr-img-caption").css("width", e) : C.parents(".fr-img-caption").css("width", ""),
                                t.match(i) ? C.parents(".fr-img-caption").css("height", t) : C.parents(".fr-img-caption").css("height", "")),
                            a && a.find("input:focus").blur(),
                            O(C);
                    }
                },
                toggleCaption: function Ne() {
                    var e;
                    if (C && !Ae()) {
                        (e = C).parent().is("a") && (e = C.parent());
                        var t,
                            a,
                            i = C.parents("ul") && 0 < C.parents("ul").length ? C.parents("ul") : C.parents("ol") && 0 < C.parents("ol").length ? C.parents("ol") : [];
                        if (0 < i.length) {
                            var n = i.find("li").length,
                                r = C.parents("li"),
                                o = document.createElement("li");
                            n - 1 === r.index() && (i.append(o), (o.innerHTML = "&nbsp;"));
                        }
                        e.attr("style") ? (a = -1 < (t = e.attr("style").split(":")).indexOf("width") ? t[t.indexOf("width") + 1].replace(";", "") : "") : e.attr("width") && (a = e.attr("width"));
                        var s = A.opts.imageResizeWithPercent ? (-1 < a.indexOf("px") ? null : a) || "100%" : C.width() + "px";
                        e.wrap(
                            '<div class="fr-img-space-wrap"><span ' +
                                (A.browser.mozilla ? "" : 'contenteditable="false"') +
                                'class="fr-img-caption ' +
                                C.attr("class") +
                                '" style="' +
                                (A.opts.useClasses ? "" : e.attr("style")) +
                                '" draggable="false"></span><p class="fr-img-space-wrap2">&nbsp;</p></div>'
                        ),
                            e.wrap('<span class="fr-img-wrap"></span>'),
                            C.after(
                                '<span class="fr-inner"'
                                    .concat(A.browser.mozilla ? "" : ' contenteditable="true"', ">")
                                    .concat(Le.START_MARKER)
                                    .concat(A.language.translate("Image Caption"))
                                    .concat(Le.END_MARKER, "</span>")
                            ),
                            C.removeAttr("class").removeAttr("style").removeAttr("width"),
                            C.parents(".fr-img-caption").css("width", s),
                            1 < C.parents(".fr-img-space-wrap").length && (Ce(document.querySelector(".fr-img-space-wrap")), Ce(document.querySelector(".fr-img-space-wrap2"))),
                            fe(!0),
                            A.selection.restore();
                    } else {
                        if (((e = we()), C.insertBefore(e), null !== e[0].querySelector("a"))) {
                            for (var l, p = e[0].querySelector("a"), f = document.createElement("a"), g = 0, c = p.attributes, d = c.length; g < d; g++) (l = c[g]), f.setAttribute(l.nodeName, l.nodeValue);
                            C.wrap(f);
                        }
                        C.attr("class", e.attr("class").replace("fr-img-caption", "")).attr("style", e.attr("style")),
                            e.remove(),
                            1 < C.parents(".fr-img-space-wrap").length && (Ce(document.querySelector(".fr-img-space-wrap")), Ce(document.querySelector(".fr-img-space-wrap2"))),
                            O(C);
                    }
                },
                hasCaption: Ae,
                exitEdit: fe,
                edit: O,
            };
        }),
        Le.DefineIcon("insertImage", { NAME: "image", SVG_KEY: "insertImage" }),
        Le.RegisterShortcut(Le.KEYCODE.P, "insertImage", null, "P"),
        Le.RegisterCommand("insertImage", {
            title: "Insert Image",
            undo: !1,
            focus: !0,
            refreshAfterCallback: !1,
            popup: !0,
            callback: function () {
                this.popups.isVisible("image.insert") ? (this.$el.find(".fr-marker").length && (this.events.disableBlur(), this.selection.restore()), this.popups.hide("image.insert")) : this.image.showInsertPopup();
            },
            plugin: "image",
        }),
        Le.DefineIcon("imageUpload", { NAME: "upload", SVG_KEY: "upload" }),
        Le.RegisterCommand("imageUpload", {
            title: "Upload Image",
            undo: !1,
            focus: !1,
            toggle: !0,
            callback: function () {
                this.image.showLayer("image-upload");
            },
            refresh: function (e) {
                this.image.refreshUploadButton(e);
            },
        }),
        Le.DefineIcon("imageByURL", { NAME: "link", SVG_KEY: "insertLink" }),
        Le.RegisterCommand("imageByURL", {
            title: "By URL",
            undo: !1,
            focus: !1,
            toggle: !0,
            callback: function () {
                this.image.showLayer("image-by-url");
            },
            refresh: function (e) {
                this.image.refreshByURLButton(e);
            },
        }),
        Le.RegisterCommand("imageInsertByURL", {
            title: "Insert Image",
            undo: !0,
            refreshAfterCallback: !1,
            callback: function () {
                this.image.insertByURL();
            },
            refresh: function (e) {
                this.image.get() ? e.text(this.language.translate("Replace")) : e.text(this.language.translate("Insert"));
            },
        }),
        Le.DefineIcon("imageDisplay", { NAME: "star", SVG_KEY: "imageDisplay" }),
        Le.RegisterCommand("imageDisplay", {
            title: "Display",
            type: "dropdown",
            options: { inline: "Inline", block: "Break Text" },
            callback: function (e, t) {
                this.image.display(t);
            },
            refresh: function (e) {
                this.opts.imageTextNear || e.addClass("fr-hidden");
            },
            refreshOnShow: function (e, t) {
                this.image.refreshDisplayOnShow(e, t);
            },
        }),
        Le.DefineIcon("image-align", { NAME: "align-left", SVG_KEY: "alignLeft" }),
        Le.DefineIcon("image-align-left", { NAME: "align-left", SVG_KEY: "alignLeft" }),
        Le.DefineIcon("image-align-right", { NAME: "align-right", SVG_KEY: "alignRight" }),
        Le.DefineIcon("image-align-center", { NAME: "align-justify", SVG_KEY: "alignCenter" }),
        Le.DefineIcon("imageAlign", { NAME: "align-justify", SVG_KEY: "alignJustify" }),
        Le.RegisterCommand("imageAlign", {
            type: "dropdown",
            title: "Align",
            options: { left: "Align Left", center: "None", right: "Align Right" },
            html: function () {
                var e = '<ul class="fr-dropdown-list" role="presentation">',
                    t = Le.COMMANDS.imageAlign.options;
                for (var a in t)
                    t.hasOwnProperty(a) &&
                        (e += '<li role="presentation"><a class="fr-command fr-title" tabIndex="-1" role="option" data-cmd="imageAlign" data-param1="'
                            .concat(a, '" title="')
                            .concat(this.language.translate(t[a]), '">')
                            .concat(this.icon.create("image-align-".concat(a)), '<span class="fr-sr-only">')
                            .concat(this.language.translate(t[a]), "</span></a></li>"));
                return (e += "</ul>");
            },
            callback: function (e, t) {
                this.image.align(t);
            },
            refresh: function (e) {
                this.image.refreshAlign(e);
            },
            refreshOnShow: function (e, t) {
                this.image.refreshAlignOnShow(e, t);
            },
        }),
        Le.DefineIcon("imageReplace", { NAME: "exchange", FA5NAME: "exchange-alt", SVG_KEY: "replaceImage" }),
        Le.RegisterCommand("imageReplace", {
            title: "Replace",
            undo: !1,
            focus: !1,
            popup: !0,
            refreshAfterCallback: !1,
            callback: function () {
                this.image.replace();
            },
        }),
        Le.DefineIcon("imageRemove", { NAME: "trash", SVG_KEY: "remove" }),
        Le.RegisterCommand("imageRemove", {
            title: "Remove",
            callback: function () {
                this.image.remove();
            },
        }),
        Le.DefineIcon("imageBack", { NAME: "arrow-left", SVG_KEY: "back" }),
        Le.RegisterCommand("imageBack", {
            title: "Back",
            undo: !1,
            focus: !1,
            back: !0,
            callback: function () {
                this.image.back();
            },
            refresh: function (e) {
                this.$;
                this.image.get() || this.opts.toolbarInline ? (e.removeClass("fr-hidden"), e.next(".fr-separator").removeClass("fr-hidden")) : (e.addClass("fr-hidden"), e.next(".fr-separator").addClass("fr-hidden"));
            },
        }),
        Le.RegisterCommand("imageDismissError", {
            title: "OK",
            undo: !1,
            callback: function () {
                this.image.hideProgressBar(!0);
            },
        }),
        Le.DefineIcon("imageStyle", { NAME: "magic", SVG_KEY: "imageClass" }),
        Le.RegisterCommand("imageStyle", {
            title: "Style",
            type: "dropdown",
            html: function () {
                var e = '<ul class="fr-dropdown-list" role="presentation">',
                    t = this.opts.imageStyles;
                for (var a in t)
                    if (t.hasOwnProperty(a)) {
                        var i = t[a];
                        "object" == _e(i) && (i = i.title),
                            (e += '<li role="presentation"><a class="fr-command" tabIndex="-1" role="option" data-cmd="imageStyle" data-param1="'.concat(a, '">').concat(this.language.translate(i), "</a></li>"));
                    }
                return (e += "</ul>");
            },
            callback: function (e, t) {
                this.image.applyStyle(t);
            },
            refreshOnShow: function (e, t) {
                var a = this.$,
                    i = this.image.getEl();
                i &&
                    t.find(".fr-command").each(function () {
                        var e = a(this).data("param1"),
                            t = i.hasClass(e);
                        a(this).toggleClass("fr-active", t).attr("aria-selected", t);
                    });
            },
        }),
        Le.DefineIcon("imageAlt", { NAME: "info", SVG_KEY: "imageAltText" }),
        Le.RegisterCommand("imageAlt", {
            undo: !1,
            focus: !1,
            popup: !0,
            title: "Alternative Text",
            callback: function () {
                this.image.showAltPopup();
            },
        }),
        Le.RegisterCommand("imageSetAlt", {
            undo: !0,
            focus: !1,
            title: "Update",
            refreshAfterCallback: !1,
            callback: function () {
                this.image.setAlt();
            },
        }),
        Le.DefineIcon("imageSize", { NAME: "arrows-alt", SVG_KEY: "imageSize" }),
        Le.RegisterCommand("imageSize", {
            undo: !1,
            focus: !1,
            popup: !0,
            title: "Change Size",
            callback: function () {
                this.image.showSizePopup();
            },
        }),
        Le.RegisterCommand("imageSetSize", {
            undo: !0,
            focus: !1,
            title: "Update",
            refreshAfterCallback: !1,
            callback: function () {
                this.image.setSize();
            },
        }),
        Le.DefineIcon("imageCaption", { NAME: "commenting", FA5NAME: "comment-alt", SVG_KEY: "imageCaption" }),
        Le.RegisterCommand("imageCaption", {
            undo: !0,
            focus: !1,
            title: "Image Caption",
            refreshAfterCallback: !0,
            callback: function () {
                this.image.toggleCaption();
            },
            refresh: function (e) {
                this.image.get() && e.toggleClass("fr-active", this.image.hasCaption());
            },
        });
});
