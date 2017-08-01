import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import './toolbox/theme.css'
import App from './App';
import registerServiceWorker from './registerServiceWorker';
import theme from "./toolbox/theme";
import ThemeProvider from "react-toolbox/lib/ThemeProvider";

ReactDOM.render(
    <ThemeProvider theme={theme}>
        <App />
    </ThemeProvider>, 
    document.getElementById('root'));
registerServiceWorker();
