const regex = /\| Version    \| `([\d\.]+)`/

module.exports.readVersion = function (contents) {
  return contents.match(regex)[1]
}

module.exports.writeVersion = function (contents, version) {
  return contents.replace(regex, '| Version    | `' + version + '`')
}
