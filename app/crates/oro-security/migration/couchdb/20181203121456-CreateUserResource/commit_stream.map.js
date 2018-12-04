function (commit) {
    if (/^oro\.security\.user\-/.test(commit._id)) {
        emit([ commit.streamId, commit.streamRevision ], 1);
    }
}