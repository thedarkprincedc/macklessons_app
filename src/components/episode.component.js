import React, { Component } from 'react';
import { List, ListItem, ListSubHeader, ListDivider } from 'react-toolbox/lib/list';
class Episode extends Component {
    
    render(){
        var title = this.props.data.title;
        var legend = (this.props.data.date)?this.props.data.date:"ddd";
        return (<ListItem
                    avatar={this.props.data.imageurl}
                    caption={title}
                    legend={legend} />);
    }
}
export default Episode;