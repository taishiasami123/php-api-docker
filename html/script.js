let url = 'http://localhost:10080/php-api/';

//ユーザー登録
const signup = () => {
  const name = document.getElementById('signupName').value;
  const bio = document.getElementById('signupBio').value;
  const email = document.getElementById('signupEmail').value;
  const password = document.getElementById('signupPwd').value;
  const password_confirmation = document.getElementById('signupCfmPwd').value
  const data = {
    sign_up_user_params: {
      name: name,
      bio: bio,
      email: email,
      password: password,
      password_confirmation: password_confirmation
    }
  };
  fetch(`${url}sign_up`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    localStorage.setItem('id', json.id);
    localStorage.setItem('token', json.token)
  });
};

// ユーザーログイン
const signin = () => {
  const email = document.getElementById('signinEmail').value;
  const password = document.getElementById('signinPwd').value;
  const passwordConfirmation = document.getElementById('signinCfmPwd').value;
  const data = {
    sign_in_user_params: {
      email: email,
      password: password,
      password_confirmation: passwordConfirmation
    }
  };
  fetch(`${url}sign_in`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    localStorage.setItem('id', json.id)
    localStorage.setItem('token', json.token)
  });
};

// ユーザー一覧
const userList = () => {
  const page = document.getElementById('userlistPageNum').value;
  const limit = document.getElementById('userlistLimit').value;
  const query = document.getElementById('userlistSearch').value;
  const list = document.getElementById('userList');
  const token = localStorage.getItem('token');
  fetch(`${url}users?page=${page}&limit=${limit}&query=${query}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Authorization': 'Bearer ' + token
    }
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    let listedObj = '';
    for (let i = 0; i < json.length; i++) {
      const obj = json[i];
      const strObj = JSON.stringify(obj.name);
      const slicedObj = strObj.slice(1, -1);
      listedObj += '<li class="list-group-item">' + slicedObj + '</li>';
    }
    list.innerHTML = listedObj;
  });
};

// 投稿一覧
const postList = () => {
  const page = document.getElementById('postPageNum').value;
  const limit = document.getElementById('postLimit').value;
  const query = document.getElementById('postSearch').value;
  const list = document.getElementById('postList');
  const token = localStorage.getItem('token');
  fetch(`${url}posts?page=${page}&limit=${limit}&query=${query}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Authorization': 'Bearer ' + token
    }
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    let listedObj = '';
    for (let i = 0; i < json.length; i++) {
      const obj = json[i];
      const strObj = JSON.stringify(obj.text);
      const slicedObj = strObj.slice(1, -1);
      listedObj += '<li class="list-group-item">' + slicedObj + '</li>';
    }
    list.innerHTML = listedObj;
  });
};

// ユーザー編集
const editUser = () => {
  const id = document.getElementById('editUserId').value
  const name = document.getElementById('editUserName').value;
  const bio = document.getElementById('editUserBio').value;
  const data = {
    user_params: {
      name: name,
      bio: bio,
    }
  };
  const token = localStorage.getItem('token');
  fetch(`${url}users/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
};

// ユーザー削除
const deleteUser = () => {
  const id = document.getElementById('deleteUserId').value;
  const token = localStorage.getItem('token');
  fetch(`${url}users/${id}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Authorization': 'Bearer ' + token
    },
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
};

// タイムライン
const timeline = () => {
  const id = document.getElementById('tlId').value;
  const page = document.getElementById('tlPageNum').value;
  const limit = document.getElementById('tlLimit').value;
  const query = document.getElementById('tlSearch').value;
  const list = document.getElementById('tlList');
  const token = localStorage.getItem('token');
  fetch(`${url}users/${id}/timeline?page=${page}&limit=${limit}&query=${query}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Authorization': 'Bearer ' + token
    }
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    let listedObj = '';
    for (let i = 0; i < json.length; i++) {
      const obj = json[i];
      const strObj = JSON.stringify(obj.text);
      const slicedObj = strObj.slice(1, -1);
      listedObj += '<li class="list-group-item">' + slicedObj + '</li>';
    }
    list.innerHTML = listedObj;
  });
};

// 投稿作成
const submitPost = () => {
  const text = document.getElementById('createPost').value;
  const data = {
    post_params: {
      text: text
    }
  };
  const token = localStorage.getItem('token');
  fetch(`${url}posts`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
}

// 投稿編集
const editPost = () => {
  const id = document.getElementById('editPostId').value;
  const text = document.getElementById('editText').value;
  const data = {
    post_params: {
      text: text
    }
  };
  const token = localStorage.getItem('token');
  fetch(`${url}posts/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
}

// 投稿削除
const deletePost = () => {
  const id = document.getElementById('deletePostId').value;
  const token = localStorage.getItem('token');
  fetch(`${url}posts/${id}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Authorization': 'Bearer ' + token
    },
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
}
