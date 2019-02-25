import React, {Component} from 'react';
import SyntaxHighlighter from 'react-syntax-highlighter';
import {brownPaper} from 'react-syntax-highlighter/dist/esm/styles/hljs';
import {Link} from "react-router-dom";

export default class QueryExecute extends Component {
    constructor(props) {
        super(props);

        this.state = {
            query: undefined,
        };

        this.renderResult = this.renderResult.bind(this);
    }

    executeQuery() {
        axios
            .get(`/queries/${this.props.match.params.id}/execute`)
            .then(response =>
                this.setState({
                    result: response.data.result,
                    error: response.data.error,
                    query: response.data.query,
                })
            );
    }

    renderResult() {
        if (this.state.error) {
            return (
                <p><strong>ERROR:</strong> {this.state.error}</p>
            );
        }

        if (!this.state.result.length) {
            return (
                <p>No rows to display.</p>
            );
        }

        console.log(this.state.result[0]);

        let columnNames = Object.keys(this.state.result[0]);

        const tableHead = columnNames.map((columnName, i) => (
            <th key={"th-" + i} className="pr-2">{columnName}</th>
        ));

        const tableBody = this.state.result.map((row, i) => (
            <tr key={"tr-" + i}>
                {columnNames.map((columnName, j) => (
                    <td key={"td-" + i + "-" + j} className="pr-2">{row[columnName]}</td>
                ))}
            </tr>
        ));

        return (
            <table className="table table-sm table-striped">
                <thead><tr>{tableHead}</tr></thead>
                <tbody>{tableBody}</tbody>
            </table>
        )
    }

    componentWillMount() {
        this.executeQuery();
    }

    render() {
        if (typeof this.state.query == "undefined") return <div />;

        return (
            <div>
                <div className="row">
                    <div className="col">
                        <h1>Query: {this.state.query.name}</h1>
                        <div className="row">
                            <div className="col query">
                                <SyntaxHighlighter
                                    language="sql"
                                    style={brownPaper}>
                                    {this.state.query.query}
                                </SyntaxHighlighter>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                <h2>Result</h2>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                {this.renderResult()}
                            </div>
                        </div>
                        <div className="row">
                            <div className="col pb-2">
                                <Link className="btn btn-primary" to="/home">
                                    Home
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
