import resolve from '@rollup/plugin-node-resolve';

export default {
  input: 'js/index.js',  // Path to your JavaScript entry file
  output: {
    file: 'js/bundle.js',  // Output file (can be placed in a 'dist' folder as well)
    format: 'esm',  // ECMAScript module format
  },
  plugins: [
    resolve()  // This plugin allows Rollup to resolve packages from node_modules
  ]
};
