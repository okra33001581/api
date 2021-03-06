define({ "api": [
  {
    "type": "get",
    "url": "/api/admin",
    "title": "显示商户列表",
    "group": "admin",
    "success": {
      "examples": [
        {
          "title": "返回商户信息列表",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  用户标识\n      \"name\": \"test\"  //字符型 用户昵称\n      \"email\": \"test@qq.com\"  // 字符型 用户email，商户登录时的email\n      \"role\": \"admin\" // 字符型 角色  可以取得值为admin或editor\n      \"avatar\": \"\" // 字符型 用户的头像图片\n    }\n  ],\n\"status\": \"success\",\n\"status_code\": 200,\n\"links\": {\n\"first\": \"http://manger.test/api/admin?page=1\",\n\"last\": \"http://manger.test/api/admin?page=19\",\n\"prev\": null,\n\"next\": \"http://manger.test/api/admin?page=2\"\n},\n\"meta\": {adminDelete\n\"current_page\": 1, // 当前页\n\"from\": 1, //当前页开始的记录\n\"last_page\": 19, //总页数\n\"path\": \"http://manger.test/api/admin\",\n\"per_page\": 15,\n\"to\": 15, //当前页结束的记录\n\"total\": 271  // 总条数\n}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/AdminController.php",
    "groupTitle": "admin",
    "name": "GetApiAdmin"
  },
  {
    "type": "get",
    "url": "/api/adminRoleList",
    "title": "取得角色列表",
    "group": "admin",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "null",
            "description": "<p>不需要参数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  null: 'null',\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "取得角色列表成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/AdminController.php",
    "groupTitle": "admin",
    "name": "GetApiAdminrolelist"
  },
  {
    "type": "post",
    "url": "/api/adminDelete",
    "title": "删除商户",
    "group": "admin",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>编号</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  id: '111111',\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/AdminController.php",
    "groupTitle": "admin",
    "name": "PostApiAdmindelete"
  },
  {
    "type": "post",
    "url": "/api/adminEdit",
    "title": "編輯管理員信息",
    "group": "admin",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/AdminController.php",
    "groupTitle": "admin",
    "name": "PostApiAdminedit"
  },
  {
    "type": "post",
    "url": "/api/adminSave",
    "title": "建立新的商户",
    "group": "admin",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/AdminController.php",
    "groupTitle": "admin",
    "name": "PostApiAdminsave"
  },
  {
    "type": "get",
    "url": "/api/permissionRuleIndex",
    "title": "权限列表取得",
    "group": "permissionRule",
    "success": {
      "examples": [
        {
          "title": "返回管理员信息列表1",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  用户标识\n      \"name\": \"test\"  //字符型 用户昵称\n      \"email\": \"test@qq.com\"  // 字符型 用户email，管理员登录时的email\n      \"role\": \"admin\" // 字符型 角色  可以取得值为admin或editor\n      \"avatar\": \"\" // 字符型 用户的头像图片\n    }\n  ],\n\"status\": \"success\",\n\"status_code\": 200,\n\"links\": {\n\"first\": \"http://manger.test/api/admin?page=1\",\n\"last\": \"http://manger.test/api/admin?page=19\",\n\"prev\": null,\n\"next\": \"http://manger.test/api/admin?page=2\"\n},\n\"meta\": {\n\"current_page\": 1, // 当前页\n\"from\": 1, //当前页开始的记录\n\"last_page\": 19, //总页数\n\"path\": \"http://manger.test/api/admin\",\n\"per_page\": 15,\n\"to\": 15, //当前页结束的记录\n\"total\": 271  // 总条数\n}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/PermissionRuleController.php",
    "groupTitle": "permissionRule",
    "name": "GetApiPermissionruleindex"
  },
  {
    "type": "get",
    "url": "/api/permissionRuleTree",
    "title": "取得权限树信息",
    "group": "permissionRule",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/PermissionRuleController.php",
    "groupTitle": "permissionRule",
    "name": "GetApiPermissionruletree"
  },
  {
    "type": "post",
    "url": "/api/permissionRuleDelete",
    "title": "权限信息删除",
    "group": "permissionRule",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/PermissionRuleController.php",
    "groupTitle": "permissionRule",
    "name": "PostApiPermissionruledelete"
  },
  {
    "type": "post",
    "url": "/api/permissionRuleEdit",
    "title": "权限信息编辑",
    "group": "permissionRule",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/PermissionRuleController.php",
    "groupTitle": "permissionRule",
    "name": "PostApiPermissionruleedit"
  },
  {
    "type": "post",
    "url": "/api/permissionRuleSave",
    "title": "权限信息保存",
    "group": "permissionRule",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/PermissionRuleController.php",
    "groupTitle": "permissionRule",
    "name": "PostApiPermissionrulesave"
  },
  {
    "type": "get",
    "url": "/api/roleIndex",
    "title": "取得角色信息列表",
    "group": "role",
    "success": {
      "examples": [
        {
          "title": "返回角色信息列表",
          "content": "HTTP/1.1 200 OK\n{\n \"data\": [\n    {\n      \"id\": 2 // 整数型  用户标识\n      \"name\": \"test\"  //字符型 用户昵称\n      \"email\": \"test@qq.com\"  // 字符型 用户email，管理员登录时的email\n      \"role\": \"admin\" // 字符型 角色  可以取得值为admin或editor\n      \"avatar\": \"\" // 字符型 用户的头像图片\n    }\n  ],\n\"status\": \"success\",\n\"status_code\": 200,\n\"links\": {\n\"first\": \"http://manger.test/api/admin?page=1\",\n\"last\": \"http://manger.test/api/admin?page=19\",\n\"prev\": null,\n\"next\": \"http://manger.test/api/admin?page=2\"\n},\n\"meta\": {\n\"current_page\": 1, // 当前页\n\"from\": 1, //当前页开始的记录\n\"last_page\": 19, //总页数\n\"path\": \"http://manger.test/api/admin\",\n\"per_page\": 15,\n\"to\": 15, //当前页结束的记录\n\"total\": 271  // 总条数\n}\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/RoleController.php",
    "groupTitle": "role",
    "name": "GetApiRoleindex"
  },
  {
    "type": "post",
    "url": "/api/roleAuth",
    "title": "角色赋值",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/RoleController.php",
    "groupTitle": "role",
    "name": "PostApiRoleauth"
  },
  {
    "type": "post",
    "url": "/api/roleAuthList",
    "title": "取得角色信息相关",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/RoleController.php",
    "groupTitle": "role",
    "name": "PostApiRoleauthlist"
  },
  {
    "type": "post",
    "url": "/api/roleDelete",
    "title": "角色信息删除",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/RoleController.php",
    "groupTitle": "role",
    "name": "PostApiRoledelete"
  },
  {
    "type": "post",
    "url": "/api/roleEdit",
    "title": "角色信息编辑",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/RoleController.php",
    "groupTitle": "role",
    "name": "PostApiRoleedit"
  },
  {
    "type": "post",
    "url": "/api/roleSave",
    "title": "角色信息保存",
    "group": "role",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/RoleController.php",
    "groupTitle": "role",
    "name": "PostApiRolesave"
  },
  {
    "type": "get",
    "url": "/api/loginInfo",
    "title": "取得登录信息",
    "group": "user",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/UserController.php",
    "groupTitle": "user",
    "name": "GetApiLogininfo"
  },
  {
    "type": "post",
    "url": "/api/loginIndex",
    "title": "登录",
    "group": "user",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/UserController.php",
    "groupTitle": "user",
    "name": "PostApiLoginindex"
  },
  {
    "type": "post",
    "url": "/api/out",
    "title": "登录注销",
    "group": "user",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "email",
            "description": "<p>用户登陆名　email格式 必须唯一</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户登陆密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "allowedValues": [
              "\"admin\"",
              "\"editor\""
            ],
            "optional": true,
            "field": "role",
            "defaultValue": "editor",
            "description": "<p>角色 内容为空或者其他的都设置为editor</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "avatar",
            "description": "<p>用户头像地址</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求的参数例子:",
          "content": "{\n  name: 'test',\n  email: '1111@qq.com',\n  password: '123456',\n  role: 'editor',\n  avatar: 'uploads/20178989.png'\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "新建用户成功",
          "content": "HTTP/1.1 201 OK\n{\n\"status\": \"success\",\n\"status_code\": 201\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "数据验证出错",
          "content": "HTTP/1.1 404 Not Found\n{\n\"status\": \"error\",\n\"status_code\": 404,\n\"message\": \"信息提交不完全或者不规范，校验不通过，请重新提交\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "/home/ok/api/app/Http/Controllers/Auz/UserController.php",
    "groupTitle": "user",
    "name": "PostApiOut"
  }
] });
