/**
 * @preserve tableExport.jquery.plugin
 *
 * Copyright (c) 2015,2016 hhurz, https://github.com/hhurz/tableExport.jquery.plugin
 * Original work Copyright (c) 2014 Giri Raj, https://github.com/kayalshri/
 *
 * Licensed under the MIT License, http://opensource.org/licenses/mit-license
 */
(function(n) {
    n.fn.extend({
      tableExport: function(t) {
        function kt(t) {
          var i = [];
          return n(t).find("thead").first().find("th").each(function(t, r) {
            i[t] = n(r).attr("data-field") !== undefined ? n(r).attr("data-field") : t.toString()
          }),
            i
        }
        function oi(t, r) {
          var u = !1;
          return i.ignoreColumn.length > 0 && (typeof i.ignoreColumn[0] == "string" ? rt.length > r && typeof rt[r] != "undefined" && n.inArray(rt[r], i.ignoreColumn) != -1 && (u = !0) : typeof i.ignoreColumn[0] == "number" && (n.inArray(r, i.ignoreColumn) != -1 || n.inArray(r - t.length, i.ignoreColumn) != -1) && (u = !0)),
            u
        }
        function p(t, r, u, f, e) {
          if (n.inArray(u, i.ignoreRow) == -1 && n.inArray(u - f, i.ignoreRow) == -1) {
            var s = n(t).filter(function() {
              return n(this).data("tableexport-display") != "none" && (n(this).is(":visible") || n(this).data("tableexport-display") == "always" || n(this).closest("table").data("tableexport-display") == "always")
            }).find(r)
              , o = 0
              , h = 0;
            if (s.each(function(t) {
              if ((n(this).data("tableexport-display") == "always" || n(this).css("display") != "none" && n(this).css("visibility") != "hidden" && n(this).data("tableexport-display") != "none") && oi(s, t) == !1 && typeof e == "function") {
                var i, f = 0, r, c = 0;
                if (typeof a[u] != "undefined" && a[u].length > 0)
                  for (i = 0; i <= t; i++)
                    typeof a[u][i] != "undefined" && (e(null, u, i),
                      delete a[u][i],
                      t++);
                for (h = t,
                     n(this).is("[colspan]") && (f = parseInt(n(this).attr("colspan")),
                       o += f > 0 ? f - 1 : 0),
                     n(this).is("[rowspan]") && (c = parseInt(n(this).attr("rowspan"))),
                       e(this, u, t),
                       i = 0; i < f - 1; i++)
                  e(null, u, t + i);
                if (c)
                  for (r = 1; r < c; r++)
                    for (typeof a[u + r] == "undefined" && (a[u + r] = []),
                           a[u + r][t + o] = "",
                           i = 1; i < f; i++)
                      a[u + r][t + o - i] = ""
              }
            }),
            typeof a[u] != "undefined" && a[u].length > 0)
              for (c = 0; c <= a[u].length; c++)
                typeof a[u][c] != "undefined" && (e(null, u, c),
                  delete a[u][c])
          }
        }
        function ti(n) {
          if (i.consoleLog === !0 && console.log(n.output()),
          i.outputMode === "string")
            return n.output();
          if (i.outputMode === "base64")
            return g(n.output());
          try {
            var t = n.output("blob");
            saveAs(t, i.fileName + ".pdf")
          } catch (r) {
            it(i.fileName + ".pdf", "data:application/pdf;base64,", n.output())
          }
        }
        function ii(n, t, i) {
          var r = 0, f, s, h, o;
          if (typeof i != "undefined" && (r = i.colspan),
          r >= 0) {
            var u = n.width
              , e = n.textPos.x
              , c = t.table.columns.indexOf(t.column);
            for (f = 1; f < r; f++)
              s = t.table.columns[c + f],
                u += s.width;
            return r > 1 && (n.styles.halign === "right" ? e = n.textPos.x + u - n.width : n.styles.halign === "center" && (e = n.textPos.x + (u - n.width) / 2)),
              n.width = u,
              n.textPos.x = e,
            typeof i != "undefined" && i.rowspan > 1 && (n.height = n.height * i.rowspan),
            (n.styles.valign === "middle" || n.styles.valign === "bottom") && (h = typeof n.text == "string" ? n.text.split(/\r\n|\r|\n/g) : n.text,
              o = h.length || 1,
            o > 2 && (n.textPos.y -= (2 - vt) / 2 * t.row.styles.fontSize * (o - 2) / 3)),
              !0
          }
          return !1
        }
        function ri(t, r, u) {
          r.each(function() {
            var r = n(this).children();
            if (n(this).is("div")) {
              var o = lt(k(this, "background-color"), [255, 255, 255])
                , s = lt(k(this, "border-top-color"), [0, 0, 0])
                , f = at(this, "border-top-width", i.jspdf.unit)
                , e = this.getBoundingClientRect()
                , h = this.offsetLeft * u.dw
                , c = this.offsetTop * u.dh
                , l = e.width * u.dw
                , a = e.height * u.dh;
              u.doc.setDrawColor.apply(undefined, s);
              u.doc.setFillColor.apply(undefined, o);
              u.doc.setLineWidth(f);
              u.doc.rect(t.x + h, t.y + c, l, a, f ? "FD" : "F")
            }
            typeof r != "undefined" && r.length > 0 && ri(t, r, u)
          })
        }
        function si(n) {
          return n.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1")
        }
        function dt(n, t, i) {
          return n.replace(new RegExp(si(t),"g"), i)
        }
        function hi(n, t, r) {
          var u = "", f, e;
          return n != null && (f = v(n, t, r),
            e = f === null || f == "" ? "" : f.toString(),
            f instanceof Date ? u = i.csvEnclosure + f.toLocaleString() + i.csvEnclosure : (u = dt(e, i.csvEnclosure, i.csvEnclosure + i.csvEnclosure),
            (u.indexOf(i.csvSeparator) >= 0 || /[\r\n ]/g.test(u)) && (u = i.csvEnclosure + u + i.csvEnclosure))),
            u
        }
        function ci(n) {
          return n = n || "0",
            n = dt(n, i.numbers.html.decimalMark, "."),
            n = dt(n, i.numbers.html.thousandsSeparator, ""),
            typeof n == "number" || jQuery.isNumeric(n) !== !1 ? n : !1
        }
        function v(t, r, u) {
          var f = "", o, h, s, a, c, e, l;
          return t != null && (o = n(t),
            h = o[0].hasAttribute("data-tableexport-value") ? o.data("tableexport-value") : o.html(),
          typeof i.onCellHtmlData == "function" && (h = i.onCellHtmlData(o, r, u, h)),
            i.htmlContent === !0 ? f = n.trim(h) : (s = h.replace(/\n/g, "\u2028").replace(/<br\s*[\/]?>/gi, "⁠"),
              a = n("<div/>").html(s).contents(),
              s = "",
              n.each(a.text().split("\u2028"), function(t, i) {
                t > 0 && (s += " ");
                s += n.trim(i)
              }),
              n.each(s.split("⁠"), function(t, i) {
                t > 0 && (f += "\n");
                f += n.trim(i).replace(/\u00AD/g, "")
              }),
            (i.numbers.html.decimalMark != i.numbers.output.decimalMark || i.numbers.html.thousandsSeparator != i.numbers.output.thousandsSeparator) && (c = ci(f),
            c !== !1 && (e = ("" + c).split("."),
            e.length == 1 && (e[1] = ""),
              l = e[0].length > 3 ? e[0].length % 3 : 0,
              f = (c < 0 ? "-" : "") + (i.numbers.output.thousandsSeparator ? (l ? e[0].substr(0, l) + i.numbers.output.thousandsSeparator : "") + e[0].substr(l).replace(/(\d{3})(?=\d)/g, "$1" + i.numbers.output.thousandsSeparator) : e[0]) + (e[1].length ? i.numbers.output.decimalMark + e[1] : "")))),
          i.escape === !0 && (f = escape(f)),
          typeof i.onCellData == "function" && (f = i.onCellData(o, r, u, f))),
            f
        }
        function li(n, t, i) {
          return t + "-" + i.toLowerCase()
        }
        function lt(n, t) {
          var i = /^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/.exec(n)
            , r = t;
          return i && (r = [parseInt(i[1]), parseInt(i[2]), parseInt(i[3])]),
            r
        }
        function ui(t) {
          var r = k(t, "text-align"), o = k(t, "font-weight"), e = k(t, "font-style"), i = "", u, f;
          return r == "start" && (r = k(t, "direction") == "rtl" ? "right" : "left"),
          o >= 700 && (i = "bold"),
          e == "italic" && (i += e),
          i == "" && (i = "normal"),
            u = {
              style: {
                align: r,
                bcolor: lt(k(t, "background-color"), [255, 255, 255]),
                color: lt(k(t, "color"), [0, 0, 0]),
                fstyle: i
              },
              colspan: parseInt(n(t).attr("colspan")) || 0,
              rowspan: parseInt(n(t).attr("rowspan")) || 0
            },
          t !== null && (f = t.getBoundingClientRect(),
            u.rect = {
              width: f.width,
              height: f.height
            }),
            u
        }
        function k(n, t) {
          try {
            return window.getComputedStyle ? (t = t.replace(/([a-z])([A-Z])/, li),
              window.getComputedStyle(n, null).getPropertyValue(t)) : n.currentStyle ? n.currentStyle[t] : n.style[t]
          } catch (i) {}
          return ""
        }
        function ai(n, t, i) {
          var u = 100, r = document.createElement("div"), f;
          return r.style.overflow = "hidden",
            r.style.visibility = "hidden",
            n.appendChild(r),
            r.style.width = u + i,
            f = u / r.offsetWidth,
            n.removeChild(r),
          t * f
        }
        function at(n, t, i) {
          var u = k(n, t)
            , r = u.match(/\d+/);
          return r !== null ? (r = r[0],
            ai(n.parentElement, r, i)) : 0
        }
        function it(n, t, i) {
          var f = window.navigator.userAgent, u, r;
          f.indexOf("MSIE ") > 0 || !!f.match(/Trident.*rv\:11\./) ? (u = document.createElement("iframe"),
          u && (document.body.appendChild(u),
            u.setAttribute("style", "display:none"),
            u.contentDocument.open("txt/html", "replace"),
            u.contentDocument.write(i),
            u.contentDocument.close(),
            u.focus(),
            u.contentDocument.execCommand("SaveAs", !0, n),
            document.body.removeChild(u))) : (r = document.createElement("a"),
          r && (r.style.display = "none",
            r.download = n,
            r.href = t.toLowerCase().indexOf("base64,") >= 0 ? t + g(i) : t + encodeURIComponent(i),
            document.body.appendChild(r),
            document.createEvent ? (ot == null && (ot = document.createEvent("MouseEvents")),
              ot.initEvent("click", !0, !1),
              r.dispatchEvent(ot)) : document.createEventObject ? r.fireEvent("onclick") : typeof r.onclick == "function" && r.onclick(),
            document.body.removeChild(r)))
        }
        function vi(n) {
          var i, r, t;
          for (n = n.replace(/\x0d\x0a/g, "\n"),
                 i = "",
                 r = 0; r < n.length; r++)
            t = n.charCodeAt(r),
              t < 128 ? i += String.fromCharCode(t) : t > 127 && t < 2048 ? (i += String.fromCharCode(t >> 6 | 192),
                i += String.fromCharCode(t & 63 | 128)) : (i += String.fromCharCode(t >> 12 | 224),
                i += String.fromCharCode(t >> 6 & 63 | 128),
                i += String.fromCharCode(t & 63 | 128));
          return i
        }
        function g(n) {
          var t = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", e = "", o, i, r, h, c, s, u, f = 0;
          for (n = vi(n); f < n.length; )
            o = n.charCodeAt(f++),
              i = n.charCodeAt(f++),
              r = n.charCodeAt(f++),
              h = o >> 2,
              c = (o & 3) << 4 | i >> 4,
              s = (i & 15) << 2 | r >> 6,
              u = r & 63,
              isNaN(i) ? s = u = 64 : isNaN(r) && (u = 64),
              e = e + t.charAt(h) + t.charAt(c) + t.charAt(s) + t.charAt(u);
          return e
        }
        var i = {
          consoleLog: !1,
          csvEnclosure: '"',
          csvSeparator: ",",
          csvUseBOM: !0,
          displayTableName: !1,
          escape: !1,
          excelstyles: [],
          fileName: "tableExport",
          htmlContent: !1,
          ignoreColumn: [],
          ignoreRow: [],
          jsonScope: "all",
          jspdf: {
            orientation: "p",
            unit: "pt",
            format: "a4",
            margins: {
              left: 20,
              right: 10,
              top: 10,
              bottom: 10
            },
            autotable: {
              styles: {
                cellPadding: 2,
                rowHeight: 12,
                fontSize: 8,
                fillColor: 255,
                textColor: 50,
                fontStyle: "normal",
                overflow: "ellipsize",
                halign: "left",
                valign: "middle"
              },
              headerStyles: {
                fillColor: [52, 73, 94],
                textColor: 255,
                fontStyle: "bold",
                halign: "center"
              },
              alternateRowStyles: {
                fillColor: 245
              },
              tableExport: {
                onAfterAutotable: null,
                onBeforeAutotable: null,
                onTable: null
              }
            }
          },
          numbers: {
            html: {
              decimalMark: ".",
              thousandsSeparator: ","
            },
            output: {
              decimalMark: ".",
              thousandsSeparator: ","
            }
          },
          onCellData: null,
          onCellHtmlData: null,
          outputMode: "file",
          tbodySelector: "tr",
          theadSelector: "tr",
          tableName: "myTableName",
          type: "csv",
          worksheetName: "xlsWorksheetName"
        }, vt = 1.15, l = this, ot = null, h = [], s = [], o = 0, a = [], u = "", rt = [], w, ut, e, nt, st, tt, y, pt, f, b, gt, bt, r;
        if (n.extend(!0, i, t),
          rt = kt(l),
        i.type == "csv" || i.type == "txt") {
          w = "";
          ut = 0;
          o = 0;
          function yt(t, r, f, e) {
            return s = n(l).find(t).first().find(r),
              s.each(function() {
                u = "";
                p(this, f, o, e + s.length, function(n, t, r) {
                  u += hi(n, t, r) + i.csvSeparator
                });
                u = n.trim(u).substring(0, u.length - 1);
                u.length > 0 && (w.length > 0 && (w += "\n"),
                  w += u);
                o++
              }),
              s.length
          }
          if (ut += yt("thead", i.theadSelector, "th,td", ut),
            ut += yt("tbody", i.tbodySelector, "td", ut),
            yt("tfoot", i.tbodySelector, "td", ut),
            w += "\n",
          i.consoleLog === !0 && console.log(w),
          i.outputMode === "string")
            return w;
          if (i.outputMode === "base64")
            return g(w);
          try {
            b = new Blob([w],{
              type: "text/" + (i.type == "csv" ? "csv" : "plain") + ";charset=utf-8"
            });
            saveAs(b, i.fileName + "." + i.type, i.type != "csv" || i.csvUseBOM === !1)
          } catch (yi) {
            it(i.fileName + "." + i.type, "data:text/" + (i.type == "csv" ? "csv" : "plain") + ";charset=utf-8," + (i.type == "csv" && i.csvUseBOM ? "﻿" : ""), w)
          }
        } else if (i.type == "sql") {
          if (o = 0,
            e = "INSERT INTO `" + i.tableName + "` (",
            h = n(l).find("thead").first().find(i.theadSelector),
            h.each(function() {
              p(this, "th,td", o, h.length, function(n, t, i) {
                e += "'" + v(n, t, i) + "',"
              });
              o++;
              e = n.trim(e);
              e = n.trim(e).substring(0, e.length - 1)
            }),
            e += ") VALUES ",
            s = n(l).find("tbody").first().find(i.tbodySelector),
            s.each(function() {
              u = "";
              p(this, "td", o, h.length + s.length, function(n, t, i) {
                u += "'" + v(n, t, i) + "',"
              });
              u.length > 3 && (e += "(" + u,
                e = n.trim(e).substring(0, e.length - 1) + "),");
              o++
            }),
            e = n.trim(e).substring(0, e.length - 1),
            e += ";",
          i.consoleLog === !0 && console.log(e),
          i.outputMode === "string")
            return e;
          if (i.outputMode === "base64")
            return g(e);
          try {
            b = new Blob([e],{
              type: "text/plain;charset=utf-8"
            });
            saveAs(b, i.fileName + ".sql")
          } catch (yi) {
            it(i.fileName + ".sql", "data:application/sql;charset=utf-8,", e)
          }
        } else if (i.type == "json") {
          if (nt = [],
            h = n(l).find("thead").first().find(i.theadSelector),
            h.each(function() {
              var n = [];
              p(this, "th,td", o, h.length, function(t, i, r) {
                n.push(v(t, i, r))
              });
              nt.push(n)
            }),
            st = [],
            s = n(l).find("tbody").first().find(i.tbodySelector),
            s.each(function() {
              var t = {}
                , i = 0;
              p(this, "td", o, h.length + s.length, function(n, r, u) {
                nt.length ? t[nt[nt.length - 1][i]] = v(n, r, u) : t[i] = v(n, r, u);
                i++
              });
              n.isEmptyObject(t) == !1 && st.push(t);
              o++
            }),
            tt = "",
            tt = i.jsonScope == "head" ? JSON.stringify(nt) : i.jsonScope == "data" ? JSON.stringify(st) : JSON.stringify({
              header: nt,
              data: st
            }),
          i.consoleLog === !0 && console.log(tt),
          i.outputMode === "string")
            return tt;
          if (i.outputMode === "base64")
            return g(tt);
          try {
            b = new Blob([tt],{
              type: "application/json;charset=utf-8"
            });
            saveAs(b, i.fileName + ".json")
          } catch (yi) {
            it(i.fileName + ".json", "data:application/json;charset=utf-8;base64,", tt)
          }
        } else if (i.type === "xml") {
          if (o = 0,
            y = '<?xml version="1.0" encoding="utf-8"?>',
            y += "<tabledata><fields>",
            h = n(l).find("thead").first().find(i.theadSelector),
            h.each(function() {
              p(this, "th,td", o, s.length, function(n, t, i) {
                y += "<field>" + v(n, t, i) + "<\/field>"
              });
              o++
            }),
            y += "<\/fields><data>",
            pt = 1,
            s = n(l).find("tbody").first().find(i.tbodySelector),
            s.each(function() {
              var n = 1;
              u = "";
              p(this, "td", o, h.length + s.length, function(t, i, r) {
                u += "<column-" + n + ">" + v(t, i, r) + "<\/column-" + n + ">";
                n++
              });
              u.length > 0 && u != "<column-1><\/column-1>" && (y += '<row id="' + pt + '">' + u + "<\/row>",
                pt++);
              o++
            }),
            y += "<\/data><\/tabledata>",
          i.consoleLog === !0 && console.log(y),
          i.outputMode === "string")
            return y;
          if (i.outputMode === "base64")
            return g(y);
          try {
            b = new Blob([y],{
              type: "application/xml;charset=utf-8"
            });
            saveAs(b, i.fileName + ".xml")
          } catch (yi) {
            it(i.fileName + ".xml", "data:application/xml;charset=utf-8;base64,", y)
          }
        } else if (i.type == "excel" || i.type == "xls" || i.type == "word" || i.type == "doc") {
          var ht = i.type == "excel" || i.type == "xls" ? "excel" : "word"
            , wt = ht == "excel" ? "xls" : "doc"
            , fi = wt == "xls" ? 'xmlns:x="urn:schemas-microsoft-com:office:excel"' : 'xmlns:w="urn:schemas-microsoft-com:office:word"'
            , ei = n(l).filter(function() {
            return n(this).data("tableexport-display") != "none" && (n(this).is(":visible") || n(this).data("tableexport-display") == "always")
          })
            , d = "";
          if (ei.each(function() {
            o = 0;
            rt = kt(this);
            d += "<table><thead>";
            h = n(this).find("thead").first().find(i.theadSelector);
            h.each(function() {
              u = "";
              p(this, "th,td", o, h.length, function(t, r, f) {
                var e, o, s;
                if (t != null) {
                  e = "";
                  u += "<th";
                  for (o in i.excelstyles)
                    i.excelstyles.hasOwnProperty(o) && (s = n(t).css(i.excelstyles[o]),
                    s != "" && s != "0px none rgb(0, 0, 0)" && (e == "" && (e = 'style="'),
                      e += i.excelstyles[o] + ":" + s + ";"));
                  e != "" && (u += " " + e + '"');
                  n(t).is("[colspan]") && (u += ' colspan="' + n(t).attr("colspan") + '"');
                  n(t).is("[rowspan]") && (u += ' rowspan="' + n(t).attr("rowspan") + '"');
                  u += ">" + v(t, r, f) + "<\/th>"
                }
              });
              u.length > 0 && (d += "<tr>" + u + "<\/tr>");
              o++
            });
            d += "<\/thead><tbody>";
            s = n(this).find("tbody").first().find(i.tbodySelector);
            s.each(function() {
              u = "";
              p(this, "td", o, h.length + s.length, function(t, r, f) {
                var o, e, s;
                if (t != null) {
                  o = "";
                  e = n(t).data("tableexport-msonumberformat");
                  typeof e == "undefined" && typeof i.onMsoNumberFormat == "function" && (e = i.onMsoNumberFormat(t, r, f));
                  typeof e != "undefined" && e != "" && (o == "" && (o = 'style="'),
                    o = 'style="mso-number-format:' + e + ";");
                  u += "<td";
                  for (s in i.excelstyles)
                    i.excelstyles.hasOwnProperty(s) && (e = n(t).css(i.excelstyles[s]),
                    e != "" && e != "0px none rgb(0, 0, 0)" && (o == "" && (o = 'style="'),
                      o += i.excelstyles[s] + ":" + e + ";"));
                  o != "" && (u += " " + o + '"');
                  n(t).is("[colspan]") && (u += ' colspan="' + n(t).attr("colspan") + '"');
                  n(t).is("[rowspan]") && (u += ' rowspan="' + n(t).attr("rowspan") + '"');
                  u += ">" + v(t, r, f) + "<\/td>"
                }
              });
              u.length > 0 && (d += "<tr>" + u + "<\/tr>");
              o++
            });
            i.displayTableName && (d += "<tr><td><\/td><\/tr><tr><td><\/td><\/tr><tr><td>" + v(n("<p>" + i.tableName + "<\/p>")) + "<\/td><\/tr>");
            d += "<\/tbody><\/table>";
            i.consoleLog === !0 && console.log(d)
          }),
            f = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' + fi + ' xmlns="http://www.w3.org/TR/REC-html40">',
            f += '<meta http-equiv="content-type" content="application/vnd.ms-' + ht + '; charset=UTF-8">',
            f += "<head>",
          ht === "excel" && (f += "<!--[if gte mso 9]>",
            f += "<xml>",
            f += "<x:ExcelWorkbook>",
            f += "<x:ExcelWorksheets>",
            f += "<x:ExcelWorksheet>",
            f += "<x:Name>",
            f += i.worksheetName,
            f += "<\/x:Name>",
            f += "<x:WorksheetOptions>",
            f += "<x:DisplayGridlines/>",
            f += "<\/x:WorksheetOptions>",
            f += "<\/x:ExcelWorksheet>",
            f += "<\/x:ExcelWorksheets>",
            f += "<\/x:ExcelWorkbook>",
            f += "<\/xml>",
            f += "<![endif]-->"),
            f += "<\/head>",
            f += "<body>",
            f += d,
            f += "<\/body>",
            f += "<\/html>",
          i.consoleLog === !0 && console.log(f),
          i.outputMode === "string")
            return f;
          if (i.outputMode === "base64")
            return g(f);
          try {
            b = new Blob([f],{
              type: "application/vnd.ms-" + i.type
            });
            saveAs(b, i.fileName + "." + wt)
          } catch (yi) {
            it(i.fileName + "." + wt, "data:application/vnd.ms-" + ht + ";base64,", f)
          }
        } else if (i.type == "png")
          html2canvas(n(l)[0], {
            allowTaint: !0,
            background: "#fff",
            onrendered: function(n) {
              var t = n.toDataURL(), u, e;
              t = t.substring(22);
              var r = atob(t)
                , f = new ArrayBuffer(r.length)
                , o = new Uint8Array(f);
              for (u = 0; u < r.length; u++)
                o[u] = r.charCodeAt(u);
              if (i.consoleLog === !0 && console.log(r),
              i.outputMode === "string")
                return r;
              if (i.outputMode === "base64")
                return g(t);
              try {
                e = new Blob([f],{
                  type: "image/png"
                });
                saveAs(e, i.fileName + ".png")
              } catch (s) {
                it(i.fileName + ".png", "data:image/png;base64,", t)
              }
            }
          });
        else if (i.type == "pdf")
          if (i.jspdf.autotable === !1)
            gt = {
              dim: {
                w: at(n(l).first().get(0), "width", "mm"),
                h: at(n(l).first().get(0), "height", "mm")
              },
              pagesplit: !1
            },
              bt = new jsPDF(i.jspdf.orientation,i.jspdf.unit,i.jspdf.format),
              bt.addHTML(n(l).first(), i.jspdf.margins.left, i.jspdf.margins.top, gt, function() {
                ti(bt)
              });
          else {
            if (r = i.jspdf.autotable.tableExport,
            typeof i.jspdf.format == "string" && i.jspdf.format.toLowerCase() === "bestfit") {
              var ft = {
                a0: [2383.94, 3370.39],
                a1: [1683.78, 2383.94],
                a2: [1190.55, 1683.78],
                a3: [841.89, 1190.55],
                a4: [595.28, 841.89]
              }
                , ct = ""
                , et = ""
                , ni = 0;
              n(l).filter(":visible").each(function() {
                var t, i;
                if (n(this).css("display") != "none" && (t = at(n(this).get(0), "width", "pt"),
                t > ni)) {
                  t > ft.a0[0] && (ct = "a0",
                    et = "l");
                  for (i in ft)
                    ft.hasOwnProperty(i) && ft[i][1] > t && (ct = i,
                      et = "l",
                    ft[i][0] > t && (et = "p"));
                  ni = t
                }
              });
              i.jspdf.format = ct == "" ? "a4" : ct;
              i.jspdf.orientation = et == "" ? "w" : et
            }
            r.doc = new jsPDF(i.jspdf.orientation,i.jspdf.unit,i.jspdf.format);
            n(l).filter(function() {
              return n(this).data("tableexport-display") != "none" && (n(this).is(":visible") || n(this).data("tableexport-display") == "always")
            }).each(function() {
              var f, u = 0, t, e;
              if (rt = kt(this),
                r.columns = [],
                r.rows = [],
                r.rowoptions = {},
              typeof r.onTable == "function" && r.onTable(n(this), i) === !1)
                return !0;
              if (i.jspdf.autotable.tableExport = null,
                t = n.extend(!0, {}, i.jspdf.autotable),
                i.jspdf.autotable.tableExport = r,
                t.margin = {},
                n.extend(!0, t.margin, i.jspdf.margins),
                t.tableExport = r,
              typeof t.beforePageContent != "function" && (t.beforePageContent = function(n) {
                  if (n.pageCount == 1) {
                    var t = n.table.rows.concat(n.table.headerRow);
                    t.forEach(function(t) {
                      t.height > 0 && (t.height += (2 - vt) / 2 * t.styles.fontSize,
                        n.table.height += (2 - vt) / 2 * t.styles.fontSize)
                    })
                  }
                }
              ),
              typeof t.createdHeaderCell != "function" && (t.createdHeaderCell = function(i, u) {
                  var f, e;
                  i.styles = n.extend({}, u.row.styles);
                  typeof r.columns[u.column.dataKey] != "undefined" && (f = r.columns[u.column.dataKey],
                  typeof f.rect != "undefined" && (i.contentWidth = f.rect.width,
                  (typeof r.heightRatio == "undefined" || r.heightRatio == 0) && (e = u.row.raw[u.column.dataKey].rowspan ? u.row.raw[u.column.dataKey].rect.height / u.row.raw[u.column.dataKey].rowspan : u.row.raw[u.column.dataKey].rect.height,
                    r.heightRatio = i.styles.rowHeight / e),
                    e = u.row.raw[u.column.dataKey].rect.height * r.heightRatio,
                  e > i.styles.rowHeight && (i.styles.rowHeight = e)),
                  typeof f.style != "undefined" && f.style.hidden !== !0 && (i.styles.halign = f.style.align,
                  t.styles.fillColor === "inherit" && (i.styles.fillColor = f.style.bcolor),
                  t.styles.textColor === "inherit" && (i.styles.textColor = f.style.color),
                  t.styles.fontStyle === "inherit" && (i.styles.fontStyle = f.style.fstyle)))
                }
              ),
              typeof t.createdCell != "function" && (t.createdCell = function(n, i) {
                  var u = r.rowoptions[i.row.index + ":" + i.column.dataKey];
                  typeof u != "undefined" && typeof u.style != "undefined" && u.style.hidden !== !0 && (n.styles.halign = u.style.align,
                  t.styles.fillColor === "inherit" && (n.styles.fillColor = u.style.bcolor),
                  t.styles.textColor === "inherit" && (n.styles.textColor = u.style.color),
                  t.styles.fontStyle === "inherit" && (n.styles.fontStyle = u.style.fstyle))
                }
              ),
              typeof t.drawHeaderCell != "function" && (t.drawHeaderCell = function(n, t) {
                  var i = r.columns[t.column.dataKey];
                  return (i.style.hasOwnProperty("hidden") != !0 || i.style.hidden !== !0) && i.rowIndex >= 0 ? ii(n, t, i) : !1
                }
              ),
              typeof t.drawCell != "function" && (t.drawCell = function(n, t) {
                  var i = r.rowoptions[t.row.index + ":" + t.column.dataKey], u;
                  return ii(n, t, i) && (r.doc.rect(n.x, n.y, n.width, n.height, n.styles.fillStyle),
                  typeof i != "undefined" && typeof i.kids != "undefined" && i.kids.length > 0 && (u = n.height / i.rect.height,
                  (u > r.dh || typeof r.dh == "undefined") && (r.dh = u),
                    r.dw = n.width / i.rect.width,
                    ri(n, i.kids, r)),
                    r.doc.autoTableText(n.text, n.textPos.x, n.textPos.y, {
                      halign: n.styles.halign,
                      valign: n.styles.valign
                    })),
                    !1
                }
              ),
                r.headerrows = [],
                h = n(this).find("thead").find(i.theadSelector),
                h.each(function() {
                  f = 0;
                  r.headerrows[u] = [];
                  p(this, "th,td", u, h.length, function(n, t, i) {
                    var e = ui(n);
                    e.title = v(n, t, i);
                    e.key = f++;
                    e.rowIndex = u;
                    r.headerrows[u].push(e)
                  });
                  u++
                }),
              u > 0 && n.each(r.headerrows[u - 1], function() {
                obj = u > 1 && this.rect == null ? r.headerrows[u - 2][this.key] : this;
                obj != null && r.columns.push(obj)
              }),
                e = 0,
                s = n(this).find("tbody").find(i.tbodySelector),
                s.each(function() {
                  var t = [];
                  f = 0;
                  p(this, "td", u, h.length + s.length, function(i, u, o) {
                    var s;
                    typeof r.columns[f] == "undefined" && (s = {
                      title: "",
                      key: f,
                      style: {
                        hidden: !0
                      }
                    },
                      r.columns.push(s));
                    typeof i != "undefined" && i != null ? (s = ui(i),
                      s.kids = n(i).children(),
                      r.rowoptions[e + ":" + f++] = s) : (s = n.extend(!0, {}, r.rowoptions[e + ":" + (f - 1)]),
                      s.colspan = -1,
                      r.rowoptions[e + ":" + f++] = s);
                    t.push(v(i, u, o))
                  });
                  t.length && (r.rows.push(t),
                    e++);
                  u++
                }),
              typeof r.onBeforeAutotable == "function")
                r.onBeforeAutotable(n(this), r.columns, r.rows, t);
              if (r.doc.autoTable(r.columns, r.rows, t),
              typeof r.onAfterAutotable == "function")
                r.onAfterAutotable(n(this), t);
              i.jspdf.autotable.startY = r.doc.autoTableEndPosY() + t.margin.top
            });
            ti(r.doc);
            typeof r.headerrows != "undefined" && (r.headerrows.length = 0);
            typeof r.columns != "undefined" && (r.columns.length = 0);
            typeof r.rows != "undefined" && (r.rows.length = 0);
            delete r.doc;
            r.doc = null
          }
        return this
      }
    })
  }
)(jQuery);
//# sourceMappingURL=tableExport.min.js.map
