define(["underscore", "Magento_PageBuilder/js/utils/object", "Magento_PageBuilder/js/content-type/appearance-config", "mage/url"], 
function (_underscore, _object, _appearanceConfig, _url) {
    /**
     * @api
     */
    class Master {
      /**
       * @param {ContentTypeInterface} contentType
       * @param {ObservableUpdater} observableUpdater
       */
      constructor(contentType, observableUpdater) {
        this.contentType = contentType;
        this.observableUpdater = observableUpdater;
        this.data = {};
        this.bindEvents();
      }
  
      /**
       * Retrieve the render template
       * @returns {string}
       */
      get template() {
        const appearanceConfig = _appearanceConfig(this.contentType.config.name, this.getData().appearance);
        return appearanceConfig.master_template;
      }
  
      /**
       * Get content type data
       * @param {string} element
       * @returns {DataObject}
       * @deprecated
       */
      getData(element) {
        let data = _underscore.extend({}, this.contentType.dataStore.getState());
        
        if (!element) {
          return data;
        }
  
        const appearanceConfiguration = _appearanceConfig(this.contentType.config.name, data.appearance);
        const config = appearanceConfiguration.elements;
        data = this.observableUpdater.convertData(data, appearanceConfiguration.converters);
        
        const result = {};
        const elementConfig = config[element]?.tag?.var;
        
        if (elementConfig) {
          result[elementConfig] = _object.get(data, elementConfig);
        }
        
        return result;
      }
  
      /**
       * Destroys current instance
       */
      destroy() {}
  
      /**
       * Attach event to updating data in data store to update observables
       */
      bindEvents() {
        this.contentType.dataStore.subscribe(() => this.updateObservables());
      }
  
      /**
       * After observables updated, allows to modify observables
       */
      afterObservablesUpdated() {}
  
      /**
       * Update observables
       * @deprecated
       */
      updateObservables() {
        const state = _underscore.extend({ name: this.contentType.config.name }, this.contentType.dataStore.getState());
        this.observableUpdater.update(this, state, this.contentType.getDataStoresStates());
        this.afterObservablesUpdated();
      }
  
      /**
       * Get the action for the newsletter
       * @returns {string}
       */
      getActionNewsletter() {
        return `${window.CUSTOM_BASE_URL}pf-newsletter/new`;
      }
    }
  
    return Master;
  });
  