/*
 * This file is part of the Kazetenn Pages Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

const Encore = require('@symfony/webpack-encore')

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

Encore
  .setOutputPath('Resources/public/')
  .setPublicPath('/')
  .setManifestKeyPrefix('bundles/kazetenn/pages')
  .enableSourceMaps(false)
  .enableVersioning(false)
  .disableSingleRuntimeChunk()
  .enableSassLoader()

  /* entries */
  .addStyleEntry('page_style', '/Resources/assets/stylesheet/page.scss')

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
