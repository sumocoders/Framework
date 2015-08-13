module.exports = function(folderToSearch, path) {
  var startOfFolderToSearch = path.indexOf(folderToSearch);

  if (startOfFolderToSearch !== -1) {
    return path.substr(startOfFolderToSearch + folderToSearch.length);
  }

  return '';
};
