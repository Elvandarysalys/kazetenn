import { startStimulusApp } from '@symfony/stimulus-bridge'
// import ModalController from './controllers/modal_controller'

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
  '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
  true,
  /\.[jt]sx?$/
));

// app.register('modal', ModalController)
// export { app }

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
