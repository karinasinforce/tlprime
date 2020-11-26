var System; !function (r) { !function (r) { !function (r) { var n = function () { function r(n) { var t = this; this.array = n, Object.defineProperty(this, "array", { value: n, writable: !1 }), this.Add = function (r) { return t.array.push(r), t.array.ToList() }, this.Aggregate = function (r) { var n, e; if (0 === (n = t.array).length) throw "Aggregate of empty array"; e = n[0]; for (var a = 1, i = n.length; i > a; ++a) e = r(e, n[a]); return e }, this.Any = function (r) { for (var n = t.array, e = 0, a = n.length; a > e; ++e) if (r(n[e])) return !0; return !1 }, this.All = function (r) { for (var n = t.array, e = 0, a = n.length; a > e; ++e) if (!r(n[e])) return !1; return !0 }, this.Average = function (r) { var n = t.array; r = r || function (r) { return r }; for (var e = 0, a = n.length, i = 0; a > i; ++i) e += r(n[i]); return e / a }, this.Concat = function (n) { return new r(t.array.concat(n)) }, this.Contains = function (r, n) { return t.Any(n ? function (t) { return n.Equals(t, r) } : function (n) { return n === r }) }, this.Count = function (r) { return r ? t.Where(r).Count() : t.array.length }, this.Distinct = function (r) { return t.DistinctBy(function (r) { return r }, r) }, this.DistinctBy = function (n, e) { for (var a, i = t.array, u = [], o = [], f = 0, h = i.length; h > f; ++f) { a = i[f]; var s = n(a); u.ToList().Contains(s, e) || (u.push(s), o.push(a)) } return new r(o) }, this.ElementAt = function (r) { if (0 > r || r >= t.array.length) throw "Index was out of range. Must be non-negative and less than the size of the collection."; return t.array[r] }, this.ElementAtOrDefault = function (r) { return r >= t.array.length || 0 > r ? null : t.array[r] }, this.Except = function (n, e) { for (var a, i, u = t.array, o = [], f = {}, h = e ? e.GetHashCode : function (r) { return Object.GetHashCode(r) }, s = 0, l = n.length; l > s; ++s) f[h(n[s])] = 1; for (var s = 0, l = u.length; l > s; ++s) a = u[s], i = h(a), f[i] || o.push(a); return new r(o) }, this.First = function (r) { if (0 === t.array.length) throw "Enumeration does not contain elements"; if (!r) return t.array[0]; var n = t.Where(r); if (0 === n.Count()) throw "Enumeration does not contain elements"; return n.ElementAt(0) }, this.FirstOrDefault = function (r) { return r ? t.Where(r).ElementAtOrDefault(0) : t.array.length > 0 ? t.array[0] : null }, this.ForEach = function (r) { for (var n = t.array, e = 0, a = n.length; a > e && r(n[e], e) !== !1; ++e); }, this.GroupBy = function (n, e, a) { e = e || function (r) { return r }, a = a || { Equals: function (r, n) { return r == n }, GetHashCode: function (r) { return Object.GetHashCode(r) } }; for (var i, u, o, f = t.array, h = {}, s = 0, l = f.length; l > s; ++s) o = void 0, i = n(f[s]), u = a.GetHashCode(i), "undefined" != typeof h[u] && (o = a.Equals(i, h[u].Key) ? u : u + s), "undefined" != typeof o && o !== u && (u = o), h[u] = h[u] || { Key: i, Elements: [] }, h[u].Elements.push(e(f[s])); for (var c = Object.keys(h), y = [], s = 0, l = c.length; l > s; ++s) y.push(h[c[s]]); return new r(y) }, this.IndexOf = function (r, n) { var e = t.array; if (n) for (var a = 0, i = e.length; i > a; ++a) { var u = e[a]; if (n.Equals(u, r)) return a } else for (var a = 0, i = e.length; i > a; ++a) if (e[a] === r) return a; return -1 }, this.Intersect = function (n, e) { for (var a = [], i = 0, u = n.length; u > i; ++i) t.Contains(n[i], e) && a.push(n[i]); return new r(a) }, this.Join = function (n, e, a, i, u) { for (var o = [], f = t.Select(e), h = n.ToList().Select(a), s = 0, l = f.Count() ; l > s; ++s) { var c = f.ElementAt(s), y = -1; if (-1 != (y = h.IndexOf(c, u))) { var g = h.ElementAt(y); o.push(i(c, g)) } } return new r(o) }, this.Last = function (r) { if (0 === t.array.length) throw "Enumeration does not contain elements"; if (!r) return t.array[t.array.length - 1]; var n = t.Where(r); if (0 === n.Count()) throw "Enumeration does not contain elements"; return n.Last() }, this.LastOrDefault = function (r) { if (0 === t.array.length) return null; if (!r) return t.array[t.array.length - 1]; var n = t.Where(r); return 0 === n.Count() ? null : n.LastOrDefault() }, this.Max = function (r) { var n = t.array; if (0 === n.length) throw "Sequence contains no elements."; r = r || function (r) { return r }; for (var e = r(n[0]), a = 0, i = n.length; i > a; ++a) { var u = r(n[a]); u > e && (e = u) } return e }, this.Min = function (r) { var n = t.array; if (0 === n.length) throw "Sequence contains no elements."; r = r || function (r) { return r }; for (var e = r(n[0]), a = 0, i = n.length; i > a; ++a) { var u = r(n[a]); e > u && (e = u) } return e }, this.OrderBy = function (r, n) { return n = n || function (r, n) { return r > n ? 1 : -1 }, t.array.sort(function (t, e) { return n(r(t), r(e)) }), t }, this.OrderByDescending = function (r, n) { return n = n || function (r, n) { return r > n ? 1 : -1 }, n = function (r) { return function (n, t) { return -r(n, t) } }(n), t.array.sort(function (t, e) { return n(r(t), r(e)) }), t }, this.RemoveAll = function (r) { return t.array.ToList().Where(function (n) { return !r(n) }) }, this.Reverse = function () { for (var r = t.array.length - 1, n = 0; r > n; ++n, --r) { var e = t.array[n]; t.array[n] = t.array[r], t.array[r] = e } return t }, this.Select = function (n) { for (var e = t.array, a = [], i = 0, u = e.length; u > i; ++i) a.push(n(e[i])); return new r(a) }, this.SelectMany = function (r, n) { for (var e = t.array, a = [], i = 0, u = e.length; u > i; ++i) a = a.concat(r(e[i])); return n ? a.ToList().Select(n) : a.ToList() }, this.SequenceEqual = function (r, n) { var e = t.array; if ("undefined" == typeof e || "undefined" == typeof r) throw "Do not pass null values to arrays."; if (e === r) return !0; if (e.length !== r.length) return !1; if (n) { for (var a = 0, i = e.length; i > a; a++) if (!n(e[a], r[a])) return !1 } else for (var a = 0, i = e.length; i > a; a++) if (e[a] !== r[a]) return !1; return !0 }, this.Single = function (r) { var n = t.array; if (!r) { if (1 != n.length) throw "Source has none or more than one element"; return n[0] } for (var e = null, a = 0, i = n.length; i > a; ++a) if (r(n[a])) { if (null != e) throw "Source has more than one element"; e = n[a] } return e }, this.SingleOrDefault = function (r, n) { var e = t.array; if (!r) return 1 != e.length ? n : e[0]; for (var a = null, i = 0, u = e.length; u > i; ++i) if (r(e[i])) { if (null != a) return n; a = e[i] } return a }, this.Skip = function (n) { return new r(t.array.slice(n)) }, this.SkipWhile = function (n) { for (var e = t.array, a = 0, i = e.length; i > a && n(e[a]) !== !1; ++a); return new r(e.slice(a)) }, this.Sum = function (r) { var n = t.array, e = 0; if (r) for (var a = 0, i = n.length; i > a; ++a) e += r(n[a]); else for (var a = 0, i = n.length; i > a; ++a) e += n[a]; return e }, this.Take = function (n) { for (var e = t.array, a = [], i = n > (i = e.length) ? i : n, u = 0; i > u; ++u) a.push(e[u]); return new r(a) }, this.TakeWhile = function (n) { for (var e, a = t.array, i = [], u = 0, o = a.length; o > u && (e = a[u], n(e)) ; ++u) i.push(e); return new r(i) }, this.Union = function (n, e) { for (var a, i, u = t.array, o = [], f = {}, h = e ? e.GetHashCode : function (r) { return Object.GetHashCode(r) }, s = 0, l = u.length; l > s; ++s) a = u[s], i = h(a), f[i] || (f[i] = a, o.push(a)); for (var s = 0, l = n.length; l > s; ++s) a = n[s], i = h(a), f[i] || (f[i] = a, o.push(a)); return new r(o) }, this.Where = function (n) { for (var e, a = t.array, i = [], u = 0, o = a.length; o > u; ++u) e = a[u], n(e) && i.push(e); return new r(i) }, this.Zip = function (n, e) { for (var a = t.array, i = [], u = a.length > n.length ? n.length : a.length, o = 0, f = u; f > o; ++o) i.push(e(a[o], n[o])); return new r(i) }, this.ToArray = function () { return t.array } } return r }(); r.List = n }(r.Generic || (r.Generic = {})); r.Generic }(r.Collections || (r.Collections = {})); r.Collections; Array.prototype.ToList = function () { return new List(this) } }(System || (System = {})); var List = System.Collections.Generic.List;