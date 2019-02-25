import React, {} from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter, Switch, Route} from 'react-router-dom';
import Index  from './components/Index';
import QueryEdit from './components/QueryEdit';
import QueryExecute from './components/QueryExecute';

if (document.getElementById('app-content')) {
    ReactDOM.render(
        <BrowserRouter>
            <div>
                <Switch>
                    <Route exact path="/home" component={Index}/>
                    <Route exact path="/add" component={QueryEdit}/>
                    <Route exact path="/:id/edit" component={QueryEdit}/>
                    <Route exact path="/:id/execute" component={QueryExecute}/>
                    <Route render={() => <h1>Not found</h1>}/>
                </Switch>
            </div>
        </BrowserRouter>
        , document.getElementById('app-content')
    );
}
