import { React, Fragment } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faSortUp,
  faSortDown,
  faSort,
} from "@fortawesome/free-solid-svg-icons";
import PropTypes from "prop-types";
import SortColumnProptypes from "../../proptypes/sort-column-proptypes";
import ColumnProptypes from "../../proptypes/column-proptypes";

/**
 * @param {object} props
 * The props.
 * @param {Array} props.columns
 * The columns for the table.
 * @param {object} props.sortColumn
 * The column to sortby.
 * @param {Function} props.onSort
 * Callback for on sort.
 * @returns {object}
 * The table body.
 */
function TableHeader({ columns, sortColumn, onSort }) {
  let { path, order } = sortColumn;

  /**
   * Sorts the rows, according to chosenpath.
   *
   * @param {object} chosenPath
   * The sorting column
   */
  function sort(chosenPath) {
    if (chosenPath === path) {
      order = order === "asc" ? "desc" : "asc";
    } else {
      path = chosenPath;
      order = "asc";
    }
    onSort({ path, order });
  }

  /**
   * Renders a search icon.
   *
   * @param {object} column
   * The sorting column.
   * @returns {object}
   * The sorting icon.
   */
  function renderSortIcon(column) {
    if (column.path !== path) {
      return (
        <FontAwesomeIcon
          style={{ color: "grey" }}
          className="search-icon"
          icon={faSort}
        />
      );
    }
    if (order === "asc") {
      return <FontAwesomeIcon className="search-icon" icon={faSortUp} />;
    }
    return <FontAwesomeIcon className="search-icon" icon={faSortDown} />;
  }
  return (
    <thead>
      <tr style={{ cursor: "pointer" }}>
        {columns.map((column) => (
          <Fragment key={column.path || column.key}>
            {column.sort && (
              <th
                id={`table-header-${column.path}`}
                onClick={() => sort(column.path)}
              >
                {column.label} {renderSortIcon(column)}
              </th>
            )}
            {!column.sort && <th>{column.label}</th>}
          </Fragment>
        ))}
      </tr>
    </thead>
  );
}

TableHeader.propTypes = {
  sortColumn: SortColumnProptypes.isRequired,
  columns: ColumnProptypes.isRequired,
  onSort: PropTypes.func.isRequired,
};

export default TableHeader;
