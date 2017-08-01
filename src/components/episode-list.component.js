import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
//import Episode from './episode.component';
import { List, ListItem, ListSubHeader, ListDivider } from 'react-toolbox/lib/list';
import {player} from "https://cdn.plyr.io/2.0.13/plyr.js";
class EpisodeList extends Component {
    constructor(props){
        super(props);
        this.state = {
             docs: []
        };
    }
    componentDidMount() {
        this.getEpisodeList().then(function(response){
            this.setState(response.data);
        }.bind(this));
    }
    getEpisodeList(){
        return axios.get('http://localhost:8080/api/getEpisodeList').then(function (response) {
            console.log(response);
            return response;
        }).catch(function (error) { console.log(error); });
    }
    handleListItem(){
        console.log(this);
    }
    render(){
        const ListTest = () => (
            <List selectable ripple>
                <ListSubHeader caption={this.props.header} />
                {
                    this.state.docs.map((obj) =>
                        <a key={obj._id} href={obj.audiourl}>
                        <ListItem
                            key={obj._id}
                            avatar={obj.imageurl}
                            caption={obj.title}
                            legend={obj.date||"444"} 
                            rightIcon='star'
                            onClick={this.handleListItem}
                            />
                        </a>
                    )     
                }
            </List>
        );

        return (
           ListTest()
        );
    }
}
export default EpisodeList;