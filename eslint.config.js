import eslint from '@eslint/js'

/**
 * @type {Array<FlatConfig>}
 */
const config = [
  eslint.configs.recommended,
  {
    files: [
      'resources/js/**/*.js'
    ],
    ignores: [
      'eslint.config.js'
    ],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        console: 'readonly',
        document: 'readonly',
        humhub: 'readonly',
        setTimeout: 'readonly'
      }
    },
    rules: {
      eqeqeq: 'error',
      'no-var': 'error',
      'prefer-const': 'error',
      quotes: ['error', 'single'],
      semi: ['error', 'never']
    }
  }
]
export default config
