const Encore = require('@symfony/webpack-encore')

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

Encore
  .setOutputPath('public/')
  .setPublicPath('bundles/kazetenncore')
  .setManifestKeyPrefix('bundles/kazetenncore')
  .enableSassLoader()

  .addEntry('admin_page_form', '/assets/js/content_form.js')
  .addEntry('app', '/assets/app.js')

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
