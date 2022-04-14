// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';


class CustomWooProducts extends Component {

  static slug = 'csh_custom_woo_products';

  render() {
    const Content = this.props.content;

    return (
      <>      
      <div dangerouslySetInnerHTML={{ __html: this.props.__page }}></div>
      </>
    );
  }
}

export default CustomWooProducts;
