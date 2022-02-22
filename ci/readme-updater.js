/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

const regex = /\| Version    \| `([\d\.]+)`/

module.exports.readVersion = function (contents) {
  return contents.match(regex)[1]
}

module.exports.writeVersion = function (contents, version) {
  return contents.replace(regex, '| Version    | `' + version + '`')
}
