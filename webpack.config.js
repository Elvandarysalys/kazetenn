const Encore = require('@symfony/webpack-encore')

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

Encore
  .setOutputPath('src/Resources/public/')
  .setPublicPath('/')
  .setManifestKeyPrefix('bundles/kazetenn')
  .enableSourceMaps(false)
  .enableVersioning(false)
  .disableSingleRuntimeChunk()
  .enableSassLoader()

  /* entries */
  .addEntry('page_form', '/src/Kazetenn/Core/Admin/Resources/assets/js/admin/page_form.js')
  .addStyleEntry('admin_style', '/src/Kazetenn/Core/Admin/Resources/assets/stylesheet/admin.scss')
  .addStyleEntry('page_style', '/src/Kazetenn/Pages/Resources/assets/stylesheet/page.scss')

  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()

  .configureBabel((config) => {
    config.plugins.push('@babel/plugin-proposal-class-properties')
  })

  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage'
    config.corejs = 3
  })

// uncomment if you use React
//.enableReactPreset()

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

module.exports = Encore.getWebpackConfig()
