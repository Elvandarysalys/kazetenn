const Encore = require('@symfony/webpack-encore')

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

Encore
  .setOutputPath('public/')
  .setPublicPath('bundles/kazetennadmin')
  .setManifestKeyPrefix('bundles/kazetennadmin')
  .enableSassLoader()

  /* entries */
  .addStyleEntry('admin_style', '/assets/stylesheet/admin.scss')
  .addEntry('admin_sidebar', '/assets/js/sidebar.js')
  .addEntry('icons', '/assets/js/icons.js')

  .enableStimulusBridge('./assets/controllers.json')

  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())

  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage'
    config.corejs = '3.23'
  })


module.exports = Encore.getWebpackConfig()
