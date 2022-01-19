const Encore = require('@symfony/webpack-encore')

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

Encore
  .setOutputPath('public/bundles/')
  .setPublicPath('/bundles')
  .setManifestKeyPrefix('bundles/kazetenn')
  .enableSourceMaps(false)
  .enableVersioning(false)
  .disableSingleRuntimeChunk()
  .enableSassLoader()

  /* entries */
  .addEntry('page_form', '/src/Core/Admin/Resources/assets/js/page_form.js')
  .addEntry('admin', '/src/Core/Admin/Resources/assets/js/admin.js')

  .addStyleEntry('admin_style', '/src/Core/Admin/Resources/assets/stylesheet/admin.scss')

  .addStyleEntry('page_style', '/src/Pages/Resources/assets/stylesheet/page.scss')

  .addStyleEntry('article_style', '/src/Articles/Resources/assets/stylesheet/article.scss')

  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()

  .configureBabel((config) => {
    config.plugins.push('@babel/plugin-proposal-class-properties')
  })

  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage'
    config.corejs = 3
  })

  .enableReactPreset()

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

module.exports = Encore.getWebpackConfig()
