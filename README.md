# Homeappliancepr WooCommerce Product Importer Plugin

## Overview

The Homeappliancepr WooCommerce Product Importer Plugin is a robust tool designed to seamlessly import products from an external API into your WooCommerce store. Initially created to import a large inventory of around 21,000 products, the plugin has been enhanced to accommodate new requirements and additional product information, resulting in a refined import list of 7,105 products.

## Features

- **Bulk Import**: Efficiently import thousands of products from an external API into WooCommerce.
- **Dynamic Updates**: Easily update product information and add new products with additional parameters.
- **Customizable Mapping**: Map external API data fields to WooCommerce product attributes.
- **Error Handling**: Robust error handling and logging for seamless imports.
- **Performance Optimized**: Designed to handle large datasets without impacting WooCommerce performance.

## Requirements

- WordPress 5.0 or higher
- WooCommerce 4.0 or higher
- PHP 7.2 or higher
- External API access credentials

## Installation

1. **Download the Plugin**: Download the latest version of the plugin from the repository.
2. **Upload to WordPress**: Go to your WordPress dashboard, navigate to Plugins > Add New, and upload the plugin zip file.
3. **Activate the Plugin**: After uploading, click 'Activate' to enable the plugin.
4. **Configure API Settings**: Go to the plugin settings page and enter your external API access credentials.

## Usage

### Initial Import

1. **Access the Import Page**: Navigate to the plugin’s import page in your WordPress dashboard.
2. **Start Import**: Click on the 'Import Products' button to start importing all products from the external API.
3. **Monitor Progress**: The import process may take some time depending on the number of products. Monitor the progress and wait for the confirmation message.

### Subsequent Imports with New Parameters

1. **Update Plugin**: Ensure you have the latest version of the plugin that supports new product parameters.
2. **Configure Additional Fields**: Go to the plugin settings and map any new fields from the external API to WooCommerce product attributes.
3. **Start New Import**: Click on the 'Import Products' button again. The plugin will import new products and update existing ones with additional information.
4. **Review Imported Products**: Check your WooCommerce store to ensure all products are imported correctly with the new parameters.

## Configuration

### API Settings

- **API Endpoint**: Enter the URL of the external API.
- **API Key**: Enter your API key or token for authentication.
- **Product Mapping**: Configure how the external API data fields should map to WooCommerce product attributes.

### Import Settings

- **Batch Size**: Set the number of products to import per batch to optimize performance.
- **Error Logging**: Enable or disable error logging to troubleshoot any issues during the import process.

## Troubleshooting

- **Common Issues**:
  - **API Connectivity**: Ensure your API credentials are correct and the endpoint is reachable.
  - **Timeouts**: For large imports, increase server timeout settings or reduce the batch size.
  - **Data Mapping**: Verify that all required fields are correctly mapped to WooCommerce attributes.

- **Logs**: Check the plugin logs for detailed error messages and troubleshooting tips.
