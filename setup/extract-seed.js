// Extract data arrays from main.jsx into JSON.
const fs = require('fs');
const vm = require('vm');

const src = fs.readFileSync('/home/nightmule/seehafen-2/src/main.jsx', 'utf8');

const blocks = {};
const wanted = [
  'primaryServices', 'additionalServices', 'homeServices', 'offerShowcaseItems',
  'soldReferences', 'rentalReferences', 'managementReferences',
  'process', 'team', 'meta', 'homegateProfileUrl'
];

for (const name of wanted) {
  // Match: const name = <literal> ;  (stop at the next top-level ';' or blank line)
  const re = new RegExp('const ' + name + ' = ([\\s\\S]*?);\\n\\n', 'm');
  const m = src.match(re);
  if (!m) { console.error('NOT FOUND:', name); continue; }
  const code = 'globalThis.__r = ' + m[1] + ';';
  const ctx = { console };
  vm.createContext(ctx);
  try {
    vm.runInContext(code, ctx);
    blocks[name] = ctx.__r;
  } catch (e) {
    console.error('EVAL FAIL', name, e.message);
  }
}

// references = sold + rental + management
blocks.references = [
  ...(blocks.soldReferences || []),
  ...(blocks.rentalReferences || []),
  ...(blocks.managementReferences || [])
];

fs.writeFileSync('/home/nightmule/seed-data.json', JSON.stringify(blocks, null, 2));
console.log('Extracted:', Object.keys(blocks).join(', '));
console.log('references:', blocks.references.length);
console.log('primaryServices:', blocks.primaryServices.length);
console.log('additionalServices:', blocks.additionalServices.length);
console.log('offers:', blocks.offerShowcaseItems.length);
console.log('team:', blocks.team.length);
