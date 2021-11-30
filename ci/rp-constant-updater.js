const regex = /const LIBRARY_VERSION = '([\d\.a]+)';/

module.exports.readVersion = function (contents) {
  return contents.match(regex)[1]
}

module.exports.writeVersion = function (contents, version) {
  return contents.replace(regex, `const LIBRARY_VERSION = '${version}';`)
}