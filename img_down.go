package main

import (
	"bytes"
	"fmt"
	"io"
	"io/ioutil"
	"net/http"
	"os"
	"regexp"
	"strconv"
	"strings"
	"sync"
)

var waitgroup sync.WaitGroup

func main() {
	args := os.Args
	if args == nil || len(args) < 2 {
		Usage() //如果用户没有输入,或参数个数不够,则调用该函数提示用户
		return
	}
	url := args[1]
	resp, err := http.Get(url)
	if err != nil {
		fmt.Println("get_url_err: " + url)
	}
	defer resp.Body.Close()
	html, _ := ioutil.ReadAll(resp.Body)
	text := string(html)
	reg := regexp.MustCompile(`src="(http://\S*?(jpg)|(jpeg)|(png))"`)
	preg := reg.FindAllStringSubmatch(text, -1)
	fmt.Println(strconv.Itoa(len(preg)) + "__img be find")
	for i := 0; i < len(preg); i++ {
		waitgroup.Add(1)
		//fmt.Println(preg[i][1])
		go getImg(preg[i][1], "./img/")

	}
	waitgroup.Wait()
}

var Usage = func() {
	fmt.Println("url must be haved!")
}

func getImg(imgurl string, filepath string) {
	path := strings.Split(imgurl, "/")
	var name string
	if len(path) > 1 {
		name = path[len(path)-1]
	}
	os.Mkdir(filepath, 0777)
	out, err := os.Create(filepath + name)
	defer out.Close()
	res, err := http.Get(imgurl)
	defer res.Body.Close()
	pix, err := ioutil.ReadAll(res.Body)
	io.Copy(out, bytes.NewReader(pix))
	if err != nil {
		fmt.Println("errimgurl: " + imgurl)
	}
	waitgroup.Done()
}
