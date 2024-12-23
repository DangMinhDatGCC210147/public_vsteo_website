/**
 * @license lucide v0.309.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */

import defaultAttributes from '../defaultAttributes.js';

const KanbanSquare = [
  "svg",
  defaultAttributes,
  [
    ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
    ["path", { d: "M8 7v7" }],
    ["path", { d: "M12 7v4" }],
    ["path", { d: "M16 7v9" }]
  ]
];

export { KanbanSquare as default };
//# sourceMappingURL=kanban-square.js.map
