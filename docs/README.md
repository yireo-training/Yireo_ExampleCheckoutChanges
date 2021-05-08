# README for Checkout

### Things to inject into your checkout-based uiComponent
- `Magento_Ui/js/model/messageList`
- `Magento_Customer/js/customer-data`
- `window.checkoutConfig`
- `stepNavigator`
- ...

### Things to manipulate on the PHP level (read-only)
- ConfigProvider via DI type or DI plugin (`window.checkoutConfig`)
- SectionPool via DI type or DI plugin (`customerData`)
- LayoutProcessor for modifying the jsLayout after static XML
